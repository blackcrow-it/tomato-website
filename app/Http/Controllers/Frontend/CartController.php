<?php

namespace App\Http\Controllers\Frontend;

use App\Book;
use App\Cart;
use App\ComboCourses;
use App\Constants\InvoiceStatus;
use App\Constants\ObjectType;
use App\Constants\PromoType;
use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCourseToCartRequest;
use App\Http\Requests\Frontend\AddToCartRequest;
use App\Http\Requests\Frontend\InstantBuyRequest;
use App\Invoice;
use App\InvoiceItem;
use App\Mail\InvoiceMail;
use App\Promo;
use App\Repositories\UserRepo;
use App\Shipment;
use App\UserComboCourse;
use App\UserCourse;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Mail;
use Throwable;

class CartController extends Controller
{
    private $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        return view('frontend.cart.index');
    }

    public function add(AddToCartRequest $request)
    {
        $type = $request->input('type');
        $object_id = $request->input('object_id');
        $amount = $request->input('amount', 1);
        if ($amount < 1) $amount = 1;

        $object = null;
        switch ($type) {
            case ObjectType::COURSE:
                $object = Course::find($object_id);
                break;

            case ObjectType::BOOK:
                $object = Book::find($object_id);
                break;

            case ObjectType::COMBO_COURSE:
                $object = ComboCourses::find($object_id);
                break;
        }

        if ($object == null) return abort(500);

        $objectInCart = Cart::where('user_id', Auth::user()->id)
            ->where('type', $type)
            ->where('object_id', $object->id)
            ->first();

        if ($objectInCart == null) {
            $objectInCart = new Cart();
            $objectInCart->user_id = Auth::user()->id;
            $objectInCart->type = $type;
            $objectInCart->object_id = $object_id;
            $objectInCart->price = $object->price;
            $objectInCart->amount = 0;
        }

        switch ($type) {
            case ObjectType::COURSE:
                $objectInCart->amount = 1;
                break;

            case ObjectType::COMBO_COURSE:
                $objectInCart->amount = 1;
                break;

            default:
                $objectInCart->amount += $amount;
                break;
        }

        DB::transaction(function () use ($objectInCart) {
            $objectInCart->save();
        });
    }

    public function delete(Request $request)
    {
        DB::transaction(function () use ($request) {
            Cart::where('user_id', Auth::user()->id)
                ->where('id', $request->input('id'))
                ->delete();
        });
    }

    public function getData()
    {
        $cart = Cart::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($item) {
                $item->object = null;
                switch ($item->type) {
                    case ObjectType::COURSE:
                        $item->object = Course::with('category')->find($item->object_id);
                        break;
                    case ObjectType::BOOK:
                        $item->object = Book::with('category')->find($item->object_id);
                        break;
                    case ObjectType::COMBO_COURSE:
                        $item->object = ComboCourses::with('category')->find($item->object_id);
                        break;
                }
                return $item;
            })
            ->filter(function ($item) {
                return $item->object != null;
            })
            ->map(function ($item) {
                $item->__object_url = $item->object->url;
                return $item;
            });

        return $cart;
    }

    public function paymentConfirm(Request $request)
    {
        $user = Auth::user();

        DB::transaction(function () use ($request, $user) {
            Cart::where('user_id', $user->id)->delete();

            foreach ($request->input('cart') as $item) {
                $this->add(new AddToCartRequest([
                    'type' => $item['type'],
                    'object_id' => $item['object_id'],
                    'amount' => $item['amount']
                ]));
            }
        });

        $cart = Cart::where('user_id', $user->id)->get();

        foreach ($cart->where('type', ObjectType::COMBO_COURSE) as $item) {
            $exists = UserComboCourse::query()
                ->where('user_id', Auth::id())
                ->where('combo_course_id', $item->object_id)
                ->exists();
            if ($exists) {
                $comboCourse = ComboCourses::find($item->object_id);
                $request->validate([
                    'cart' => [
                        function ($attribute, $value, $fail) use ($comboCourse) {
                            $fail('Bạn đã sở hữu combo khóa học <b>' . $comboCourse->title . '</b>. Vui lòng gỡ khỏi giỏ hàng trước khi thanh toán.');
                        }
                    ]
                ]);
            }
        }

        foreach ($cart->where('type', ObjectType::COURSE) as $item) {
            $exists = UserCourse::query()
                ->where('user_id', Auth::id())
                ->where('course_id', $item->object_id)
                ->where('expires_on', '>', now())
                ->exists();
            if ($exists) {
                $course = Course::find($item->object_id);
                $request->validate([
                    'cart' => [
                        function ($attribute, $value, $fail) use ($course) {
                            $fail('Bạn đã sở hữu khóa học <b>' . $course->title . '</b>. Vui lòng gỡ khỏi giỏ hàng trước khi thanh toán.');
                        }
                    ]
                ]);
            }
        }

        $promo = null;
        if ($request->input('promo_code')) {
            $promo = Promo::query()
                ->where('code', $request->input('promo_code'))
                ->where('start_on', '<', now())
                ->where('expires_on', '>', now())
                ->first();

            if ($promo == null) {
                $request->validate([
                    'cart' => [
                        function ($attribute, $value, $fail) {
                            $fail('Mã khuyến mãi đã hết hạn hoặc không tồn tại.');
                        }
                    ]
                ]);
            }

            if (
                // Kiểm tra một người dùng
                ($promo->only_one_user == true && $promo->invoices()->where('user_id', '!=', Auth::id())->exists()) ||
                // Kiểm tra dùng một lần
                ($promo->used_many_times == false && $promo->invoices()->where('user_id', Auth::id())->exists())
            ) {
                $request->validate([
                    'cart' => [
                        function ($attribute, $value, $fail) {
                            $fail('Mã khuyến mãi đã được sử dụng. Vui lòng không sử dụng lại.');
                        }
                    ]
                ]);
            }

            if (!empty($promo->combo_courses)) {
                $buyCourseIds = $cart->where('type', ObjectType::COURSE)->pluck('object_id');
                $courseIdsNotInCombo = collect($promo->combo_courses)->filter(function ($courseId) use ($buyCourseIds) {
                    return !$buyCourseIds->contains($courseId);
                });
                if (!$courseIdsNotInCombo->isEmpty()) {
                    $request->validate([
                        'cart' => [
                            function ($attribute, $value, $fail) {
                                $fail('Giỏ hàng không đủ điều kiện để áp dụng mã khuyến mại.');
                            }
                        ]
                    ]);
                }
            }
        }

        if ($promo) {
            $cart->transform(function ($item) use ($promo) {
                if ($item->type != ObjectType::COURSE) return $item;

                switch ($promo->type) {
                    case PromoType::DISCOUNT:
                        $item->price = ceil($item->price - $item->price * $promo->value / 100);
                        break;

                    case PromoType::SAME_PRICE:
                        $item->price = min($item->price, $promo->value);
                        break;
                }

                return $item;
            });
        }

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item->amount * $item->price;
        }

        if (!$request->input('shipment')['is_cod'] && $totalPrice > $user->money) {
            $request->validate([
                'cart' => [
                    function ($attribute, $value, $fail) {
                        $fail('Số dư trong tài khoản của bạn không đủ. Vui lòng <a href="' . route('user.recharge') . '" target="_blank"><b>nạp thêm vào tài khoản</b></a>.');
                    }
                ]
            ]);
        }

        try {
            DB::beginTransaction();

            $shipInfo = $request->input('ship_info');
            $shipmentInfo = $request->input('shipment');

            // Process courses
            $coursesInCart = $cart->where('type', ObjectType::COURSE);
            if ($coursesInCart->count() > 0) {
                $invoice = new Invoice();
                $invoice->user_id = $user->id;
                $invoice->name = $shipInfo['name'] ?? $user->name;
                $invoice->phone = $shipInfo['phone'] ?? $user->phone;
                $invoice->shipping = false;
                $invoice->status = InvoiceStatus::COMPLETE;
                $invoice->promo_id = $promo->id ?? null;
                $invoice->save();

                foreach ($coursesInCart as $item) {
                    $invoiceItem = new InvoiceItem();
                    $invoiceItem->invoice_id = $invoice->id;
                    $invoiceItem->type = $item->type;
                    $invoiceItem->object_id = $item->object_id;
                    $invoiceItem->amount = $item->amount;
                    $invoiceItem->price = $item->price;
                    $invoiceItem->save();
                }

                $courseIds = $coursesInCart->pluck('object_id');
                $courses = Course::whereIn('id', $courseIds)->get();
                foreach ($courses as $course) {
                    $userCourse = new UserCourse();
                    $userCourse->user_id = $user->id;
                    $userCourse->course_id = $course->id;
                    $userCourse->expires_on = $course->buyer_days_owned ? now()->addDays($course->buyer_days_owned) : null;
                    $userCourse->save();
                }
            }

            // Notification invoices
            $notificationInvoices = [];

            // Process books
            $booksInCart = $cart->where('type', ObjectType::BOOK);
            if ($booksInCart->count() > 0) {
                $invoice = new Invoice();
                $invoice->user_id = $user->id;
                $invoice->name = $shipInfo['name'] ?? $user->name;
                $invoice->phone = $shipInfo['phone'] ?? $user->phone;
                $invoice->shipping = $shipInfo['shipping'] ?? false;
                $invoice->city = $invoice->shipping ? $shipInfo['city'] : null;
                $invoice->district = $invoice->shipping ? $shipInfo['district'] : null;
                $invoice->address = $invoice->shipping ? $shipInfo['address'] : null;
                $invoice->status = InvoiceStatus::PENDING;
                $invoice->promo_id = $promo->id ?? null;
                $invoice->save();

                foreach ($booksInCart as $item) {
                    $invoiceItem = new InvoiceItem();
                    $invoiceItem->invoice_id = $invoice->id;
                    $invoiceItem->type = $item->type;
                    $invoiceItem->object_id = $item->object_id;
                    $invoiceItem->amount = $item->amount;
                    $invoiceItem->price = $item->price;
                    $invoiceItem->save();
                }


                if ($shipmentInfo && $shipInfo['shipping']) {
                    $shipment = new Shipment();
                    $shipment->partner = 'GHTK';
                    $shipment->ship_money = $shipmentInfo['ship_money'];
                    $shipment->is_fast = $shipmentInfo['is_fast'];
                    $shipment->is_ship_cod = $shipmentInfo['is_cod'];
                    $shipment->invoice_id = $invoice->id;
                    $shipment->save();
                }

                $notificationInvoices[] = $invoice;
            }

            // Process combos course
            $combosCourseInCart = $cart->where('type', ObjectType::COMBO_COURSE);
            if ($combosCourseInCart->count() > 0) {
                $invoice = new Invoice();
                $invoice->user_id = $user->id;
                $invoice->name = $shipInfo['name'] ?? $user->name;
                $invoice->phone = $shipInfo['phone'] ?? $user->phone;
                $invoice->shipping = false;
                $invoice->status = InvoiceStatus::COMPLETE;
                $invoice->promo_id = $promo->id ?? null;
                $invoice->save();

                foreach ($combosCourseInCart as $item) {
                    $invoiceItem = new InvoiceItem();
                    $invoiceItem->invoice_id = $invoice->id;
                    $invoiceItem->type = $item->type;
                    $invoiceItem->object_id = $item->object_id;
                    $invoiceItem->amount = $item->amount;
                    $invoiceItem->price = $item->price;
                    $invoiceItem->save();
                }

                $comboCourseIds = $combosCourseInCart->pluck('object_id');
                $combosCourse = ComboCourses::whereIn('id', $comboCourseIds)->get();
                foreach ($combosCourse as $comboCourse) {
                    $userComboCourse = new UserComboCourse();
                    $userComboCourse->user_id = $user->id;
                    $userComboCourse->combo_course_id = $comboCourse->id;
                    $userComboCourse->save();
                    $addDayBuy = 0;
                    foreach ($comboCourse->items as $itemComboCourse) {
                        $itemCourse = $itemComboCourse->course;
                        $userCourseInCombo = UserCourse::where('course_id', $itemCourse->id)->where('user_id', $user->id)->firstOrNew();
                        $userCourseInCombo->user_id = $user->id;
                        $userCourseInCombo->course_id = $itemCourse->id;
                        if (count($comboCourse->items) >= 3 && $itemCourse->buyer_days_owned) {
                            $addDayBuy += 30;
                        }
                        $userCourseInCombo->expires_on = $itemCourse->buyer_days_owned ? now()->addDays($itemCourse->buyer_days_owned)->addDays($addDayBuy) : null;
                        $userCourseInCombo->save();
                    }
                }
            }

            Cart::where('user_id', $user->id)->delete();

            if ($shipmentInfo && $shipInfo['shipping']) {
                $totalPrice += $shipmentInfo['ship_money'];
            }
            if ($shipmentInfo && !$shipmentInfo['is_cod']) {
                $this->userRepo->removeMoney($user->id, $totalPrice);
            }

            DB::commit();

            if (config('settings.email_notification')) {
                foreach ($notificationInvoices as $nInvoice) {
                    Mail::to(config('settings.email_notification'))
                        ->send(
                            new InvoiceMail([
                                'invoice' => $nInvoice
                            ])
                        );
                }
            }
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error($th);

            $request->validate([
                'cart' => [
                    function ($attribute, $value, $fail) {
                        $fail('Có lỗi xảy ra. Vui lòng thử lại.');
                    }
                ]
            ]);
        }
    }

    public function paymentComplete()
    {
        return view('frontend.cart.complete');
    }

    public function instantBuy(InstantBuyRequest $request)
    {
        $course = Course::find($request->input('course_id'));
        $user = Auth::user();

        try {
            DB::beginTransaction();

            Cart::where([
                'object_id' => $course->id,
                'user_id' => $user->id,
                'type' => ObjectType::COURSE
            ])->delete();

            $invoice = new Invoice();
            $invoice->user_id = $user->id;
            $invoice->name = $user->name;
            $invoice->phone = $user->phone;
            $invoice->shipping = false;
            $invoice->status = InvoiceStatus::COMPLETE;
            $invoice->save();

            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->type = ObjectType::COURSE;
            $invoiceItem->object_id = $course->id;
            $invoiceItem->amount = 1;
            $invoiceItem->price = $course->price;
            $invoiceItem->save();

            $userCourse = new UserCourse();
            $userCourse->user_id = $user->id;
            $userCourse->course_id = $course->id;
            $userCourse->expires_on = $course->buyer_days_owned ? now()->addDays($course->buyer_days_owned) : null;
            $userCourse->save();

            $this->userRepo->removeMoney($user->id, $course->price);

            DB::commit();

            return redirect()
                ->route('course', ['id' => $course->id, 'slug' => $course->slug])
                ->with('success', 'Bạn đã mua khóa học thành công.');
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return redirect()
                ->route('course', ['id' => $course->id, 'slug' => $course->slug])
                ->withErrors('Có lỗi xảy ra trong quá trình mua khóa học. Vui lòng thử lại.');
        }
    }

    public function getPromo(Request $request)
    {
        $promo = Promo::query()
            ->where('code', $request->input('code'))
            ->where('start_on', '<', now())
            ->where('expires_on', '>', now())
            ->first();

        if ($promo == null) {
            $request->validate([
                'code' => [
                    function ($attribute, $value, $fail) {
                        $fail('Mã khuyến mãi đã hết hạn hoặc không tồn tại.');
                    }
                ]
            ]);
        }

        if (
            // Kiểm tra một người dùng
            ($promo->only_one_user == true && $promo->invoices()->where('user_id', '!=', Auth::id())->exists()) ||
            // Kiểm tra dùng một lần
            ($promo->used_many_times == false && $promo->invoices()->where('user_id', Auth::id())->exists())
        ) {
            $request->validate([
                'code' => [
                    function ($attribute, $value, $fail) {
                        $fail('Mã khuyến mãi đã được sử dụng. Vui lòng không sử dụng lại.');
                    }
                ]
            ]);
        }

        return $promo;
    }
}
