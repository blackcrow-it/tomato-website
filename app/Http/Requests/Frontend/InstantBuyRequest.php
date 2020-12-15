<?php

namespace App\Http\Requests\Frontend;

use App\Cart;
use App\Constants\ObjectType;
use App\Course;
use App\UserCourse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InstantBuyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course_id' => [
                'bail',
                'required',
                Rule::exists('courses', 'id')->where(function ($query) {
                    $query->where('enabled', true);
                }),
                function ($attribute, $value, $fail) {
                    $course = Course::find($value);
                    if (auth()->user()->money >= $course->price) return;
                    return $fail('Số dư trong tài khoản của bạn không đủ. Vui lòng <a href="' . route('user.recharge') . '" target="_blank"><b>nạp thêm vào tài khoản</b></a>.');
                }
            ]
        ];
    }
}
