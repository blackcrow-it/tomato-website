<?php

namespace App\Http\Requests\Frontend;

use App\Cart;
use App\Constants\ObjectType;
use App\Course;
use App\UserCourse;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $type = $this->input('type');
        $object_id = $this->input('object_id');

        return [
            'object_id' => [
                function ($attribute, $value, $fail) use ($type, $object_id) {
                    if ($type != ObjectType::COURSE) return;

                    $courseExists = Course::where([
                        'id' => $object_id,
                        'enabled' => true
                    ])->exists();
                    if (!$courseExists) {
                        return $fail('Khóa học không tồn tại.');
                    }

                    $isUserOwned = UserCourse::where([
                        'course_id' => $object_id,
                        'user_id' => auth()->id(),
                    ])->exists();
                    if ($isUserOwned) {
                        return $fail('Bạn đã sở hữu khóa học này rồi.');
                    }
                }
            ]
        ];
    }
}
