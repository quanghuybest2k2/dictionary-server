<?php

namespace App\Http\Requests\MeanRequest;

use App\Http\Requests\FormRequest;

class StoreMeanRequest extends FormRequest
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
        return [
            'word_id' => ['required', 'exists:words,id', 'integer'],
            'word_type_id' => ['required', 'exists:word_types,id', 'integer'],
            'means' => ['required', 'string', 'min:1', 'max:200'],
            'description' => ['nullable', 'string', 'min:1', 'max:1000'],
            'example' => ['nullable', 'string', 'min:1', 'max:500'],
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
            'word_id' => 'Id từ',
            'word_type_id' => 'Id từ loại',
            'means' => 'Nghĩa',
            'description' => 'Mô tả',
            'example' => 'Ví dụ',
        ];
    }
}
