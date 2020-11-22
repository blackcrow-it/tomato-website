<?php

namespace App\Http\Controllers\Frontend;

use App\Book;
use App\Cart;
use App\Constants\InvoiceStatus;
use App\Constants\ObjectType;
use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCourseToCartRequest;
use App\Invoice;
use App\InvoiceItem;
use App\Repositories\UserRepo;
use App\UserCourse;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

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

    public function add(Request $request)
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
                $item->__enabled_change_amount = true;
                switch ($item->type) {
                    case ObjectType::COURSE:
                        $item->object = Course::with('category')->find($item->object_id);
                        $item->__enabled_change_amount = false;
                        break;
                    case ObjectType::BOOK:
                        $item->object = Book::with('category')->find($item->object_id);
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
                $this->add(new Request([
                    'type' => $item['type'],
                    'object_id' => $item['object_id'],
                    'amount' => $item['amount']
                ]));
            }
        });

        $cart = Cart::where('user_id', $user->id)->get();

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item->amount * $item->price;
        }

        if ($totalPrice > $user->money) {
            return response('Số tiền trong tài khoản không đủ, vui lòng nạp thêm để tiếp tục mua hàng.', 500);
        }

        try {
            DB::beginTransaction();

            $shipInfo = $request->input('ship_info');

            // Process courses
            $coursesInCart = $cart->where('type', ObjectType::COURSE);
            if ($coursesInCart->count() > 0) {
                $invoice = new Invoice();
                $invoice->user_id = $user->id;
                $invoice->name = $shipInfo['name'] ?? $user->name;
                $invoice->phone = $shipInfo['phone'] ?? $user->phone;
                $invoice->shipping = false;
                $invoice->status = InvoiceStatus::COMPLETE;
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

            // Process books
            $booksInCart = $cart->where('type', ObjectType::BOOK);
            if ($booksInCart->count() > 0) {
                $invoice = new Invoice();
                $invoice->user_id = $user->id;
                $invoice->name = $shipInfo['name'] ?? $user->name;
                $invoice->phone = $shipInfo['phone'] ?? $user->phone;
                $invoice->shipping = $shipInfo['shipping'];
                $invoice->city = $shipInfo['city'];
                $invoice->district = $shipInfo['district'];
                $invoice->address = $shipInfo['address'];
                $invoice->status = InvoiceStatus::PENDING;
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
            }

            Cart::where('user_id', $user->id)->delete();

            $this->userRepo->removeMoney($user->id, $totalPrice);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return response('Có lỗi xảy ra, vui lòng thử lại.', 500);
        }
    }

    public function paymentComplete() {
        return view('frontend.cart.complete');
    }
}
