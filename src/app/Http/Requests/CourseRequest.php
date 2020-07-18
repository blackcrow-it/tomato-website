<?php

namespace App\Http\Requests;

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
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'enabled' => 'required|boolean',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'og_title' => 'nullable|string',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|url',
            'price' => 'nullable|numeric|min:0',
            'course_videos' => 'nullable|array',
            'course_videos.*.title' => 'required|string',
            'course_videos.*.original_path' => 'required|string'
        ];
    }
}
