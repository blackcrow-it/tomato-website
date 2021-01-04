<?php

namespace App\Http\Requests\Backend;

use App\Constants\PromoType;
use App\Promo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromoRequest extends FormRequest
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
            'promo.code' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Promo::class, 'code')->ignore($this->route('id')),
            ],
            'promo.type' => [
                Rule::in([
                    PromoType::DISCOUNT,
                    PromoType::SAME_PRICE
                ])
            ],
            'promo.value' => 'required|numeric',
            'combo_courses' => 'nullable|array',
            'combo_courses.*' => [
                Rule::exists('courses', 'id')->where('enabled', true),
            ],
            'promo.expires_on' => 'required|date_format:Y-m-d H:i',
            'promo.used_many_times' => 'required:boolean'
        ];
    }
}
