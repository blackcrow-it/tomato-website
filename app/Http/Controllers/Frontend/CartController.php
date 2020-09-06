<?php

namespace App\Http\Controllers\Frontend;

use App\Cart;
use App\Constants\ObjectType;
use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCourseToCartRequest;
use App\Invoice;
use App\InvoiceItem;
use Auth;
use DB;
use Illuminate\Http\Request;

class CartController extends Controller
{
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

    public function submitCart(Request $request)
    {
        DB::transaction(function () use ($request) {
            Cart::where('user_id', Auth::user()->id)->delete();

            foreach ($request->input('cart') as $item) {
                $this->add(new Request([
                    'type' => $item['type'],
                    'object_id' => $item['object_id'],
                    'amount' => $item['amount']
                ]));
            }
        });
    }

    public function paymentConfirm()
    {
        return view('frontend.cart.confirm');
    }

    public function paymentComplete()
    {
        DB::transaction(function () {
            $user = Auth::user();

            $cart = Cart::where('user_id', $user->id)->get();

            $invoice = new Invoice();
            $invoice->user_id = $user->id;
            $invoice->save();

            foreach ($cart as $item) {
                $invoiceItem = new InvoiceItem();
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->object_id = $item->object_id;
                $invoiceItem->amount = $item->amount;
                $invoiceItem->price = $item->price;
                $invoiceItem->save();
            }

            Cart::where('user_id', $user->id)->delete();
        });

        return view('frontend.cart.complete');
    }
}
