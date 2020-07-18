<?php

namespace App\Http\Controllers\Frontend;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCourseToCartRequest;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart') ?? collect();

        $courses = Course::where('enabled', true)
            ->whereIn('id', $cart)
            ->orderByRaw('array_position(ARRAY[' . $cart->join(',') . ']::int8[], id)')
            ->get();

        return view('frontend.cart.index', [
            'list' => $courses
        ]);
    }

    public function add(AddCourseToCartRequest $request)
    {
        $cart = session()->get('cart') ?? collect();

        if ($cart->where('course_id', $request->input('course_id'))->count() == 0) {
            $cart->push($request->input('course_id'));

            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart') ?? collect();

        $cart = $cart->reject(function ($courseId) use ($request) {
            return $courseId == $request->input('course_id');
        });

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }
}
