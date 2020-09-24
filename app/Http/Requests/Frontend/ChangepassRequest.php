<?php

namespace App\Http\Requests\Frontend;

use Auth;
use Hash;
use Illuminate\Foundation\Http\FormRequest;

class ChangepassRequest extends FormRequest
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
            'old_pass' => [
                'required',
                function ($attribute, $value, $fail) {
                    $valid = Hash::check($value, Auth::user()->password);
                    if ($valid) return;
                    $fail('Mật khẩu cũ không chính xác.');
                }
            ],
            'new_pass' => 'required|string|min:6|confirmed'
        ];
    }
}
