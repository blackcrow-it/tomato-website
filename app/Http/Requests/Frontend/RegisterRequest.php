<?php

namespace App\Http\Requests\Frontend;

use App\Rules\Lowercase;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'username' => 'required|string|between:5,255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'name' => 'nullable|string',
            'email' => [
                'nullable',
                'email',
                'max:255',
                new Lowercase,
                'unique:users,email'
            ],
            'phone' => 'nullable|numeric',
            'checkbox_requirement' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'checkbox_requirement.required' => 'Bạn phải đồng ý với các điều khoản sử dụng và chính sách bảo mật của Tomato Online',
        ];
    }
}
