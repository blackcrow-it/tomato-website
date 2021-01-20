<?php

namespace App\Http\Requests\Backend;

use App\Rules\Lowercase;
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
        $userId = $this->route('id');

        return [
            'username' => [
                'required',
                'string',
                'between:5,255',
                Rule::unique('users', 'username')->ignore($userId ?? null)
            ],
            'name' => 'nullable|string',
            'email' => [
                'nullable',
                'email',
                'max:255',
                new Lowercase,
                Rule::unique('users', 'email')->ignore($userId ?? null)
            ],
            'password' => [
                $userId ? 'nullable' : 'required',
                'string',
                'min:6'
            ],
            'money' => 'nullable|numeric|min:0',
            '__user_courses' => 'nullable|array',
            '__user_courses.*.course_id' => 'exists:courses,id',
            '__user_courses.*.expires_on' => 'nullable|date_format:Y-m-d H:i:s',
            '__user_courses.*.created_at' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    }
}
