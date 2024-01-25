<?php

namespace App\Http\Requests\Reviews;

use App\Http\Requests\FormRequest;

class ReviewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return
            [
                'user_id' => ['required', 'exists:users,id'],
                'rating' => ['required', 'integer', 'min:1', 'max:5'],
                'comment' => ['nullable', 'string']
            ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Trường :attribute là bắt buộc.',
            'min' => 'Trường :attribute tối thiểu :min ký tự.',
            'max' => 'Trường :attribute không được vượt quá :max ký tự.',
            'integer' => 'Trường :attribute phải là số nguyên.',
            'string' => 'Trường :attribute phải là chuỗi ký tự.',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'Id người dùng',
            'rating' => 'Đánh giá',
            'comment' => 'Phản hồi',
        ];
    }
}
