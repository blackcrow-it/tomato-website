<?php

namespace App\Http\Requests\Backend;

use App\Constants\PartType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PartRequest extends FormRequest
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
            'enabled' => 'required|boolean',
            'type' => [
                'required',
                Rule::in([
                    PartType::VIDEO,
                    PartType::YOUTUBE,
                    PartType::CONTENT,
                    PartType::TEST,
                    PartType::SURVEY,
                ]),
            ],
        ];
    }
}
