<?php

namespace App\Http\Requests;

use App\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
            'parent_id' => 'nullable|numeric|exists:categories,id',
            'title' => 'required|string',
            'slug' => 'nullable|string',
            'icon' => 'nullable|string',
            'cover' => 'nullable|url',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'og_title' => 'nullable|string',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|url',
            'type' => [
                'required_if:parent_id,',
                Rule::in([Category::TYPE_COURSE, Category::TYPE_POST]),
            ]
        ];
    }
}
