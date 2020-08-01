<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
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
            '__template_position' => 'nullable|array',
            '__template_position.*' => [
                Rule::in(collect(get_template_position('post'))->pluck('code'))
            ]
        ];
    }
}
