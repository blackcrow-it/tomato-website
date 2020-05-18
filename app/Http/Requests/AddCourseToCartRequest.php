<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddCourseToCartRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'course_id' => [
                'required',
                Rule::exists('courses', 'id')->where(function ($query) {
                    $query->where('enabled', true);
                })
            ],
        ];
    }
}
