<?php

namespace App\Http\Requests\WordRequest;

use App\Http\Requests\FormRequest;

class StoreWordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // true: cho phép ai cũng có thể gửi request đăng nhập, admin và user
        // false: chỉ admin có quyền đăng nhập
        return true;
    }
    public function rules(): array
    {
        return [
            'word_name' => 'required|max:255',
            'specialization_id' => 'required|integer',
            'synonymous' => 'nullable|max:255|string',
            'antonyms' => 'nullable|max:255|string',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Trường :attribute là bắt buộc.',
            'max' => 'Trường :attribute không được vượt quá :max ký tự.',
            'integer' => 'Trường :attribute phải là số nguyên.',
            'string' => 'Trường :attribute phải là chuỗi ký tự.',
        ];
    }

    public function attributes(): array
    {
        return [
            'word_name' => 'Từ vựng',
            'specialization_id' => 'Id chuyên ngành',
            'synonymous' => 'Từ đồng nghĩa',
            'antonyms' => 'Từ trái nghĩa',
        ];
    }
}
