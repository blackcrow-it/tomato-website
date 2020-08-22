<?php

namespace App\Http\Requests\Backend;

use App\Constants\ObjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
            'title' => 'required|string',
            'slug' => 'nullable|string',
            'thumbnail' => 'nullable|url',
            'cover' => 'nullable|url',
            'intro_youtube_id' => 'nullable|string',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'enabled' => 'required|boolean',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'og_title' => 'nullable|string',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|url',
            'price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            '__template_position' => 'nullable|array',
            '__template_position.*' => [
                Rule::in(collect(get_template_position(ObjectType::COURSE))->pluck('code'))
            ]
        ];
    }
}
