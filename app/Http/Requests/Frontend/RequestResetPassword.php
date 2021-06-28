<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class RequestResetPassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|string|min:6',
            'password_confirm' => 'required|string|min:6|same:password'
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Trường này không được để trống',
            'password.string' => 'Bạn phải nhập vào kiểu chuỗi',
            'password.min' => 'Bạn phải nhập tối thiểu 6 kí tự',
            'password_confirm.required' => 'Trường này không được để trống',
            'password_confirm.string' => 'Bạn phải nhập vào kiểu chuỗi',
            'password_confirm.min' => 'Bạn phải nhập tối thiểu 6 kí tự',
            'password_confirm.same' => 'Phải nhập giống trường password',
        ];
    }
}
