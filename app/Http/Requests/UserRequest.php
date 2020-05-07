<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $user = $this->route('user');

        return [
            'username' => [
                'required',
                'string',
                'between:5,255',
                Rule::unique('users', 'username')->ignore($user->id ?? null)
            ],
            'name' => 'nullable|string',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id ?? null)
            ],
            'password' => [
                $user ? 'nullable' : 'required',
                'string',
                'min:6'
            ]
        ];
    }
}
