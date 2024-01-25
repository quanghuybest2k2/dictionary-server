<?php

namespace App\Http\Requests\LoveRequest;

use App\Http\Requests\FormRequest;

class CheckIfExistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'english' => 'required|max:400',
            'user_id' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Vui lòng nhập :attribute.',
            'min' => ':attribute ít nhất :min ký tự.',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'user_id.integer' => ':attribute phải là số nguyên dương.',
        ];
    }

    public function attributes(): array
    {
        return [
            'english' => 'Từ khóa tiếng anh',
            'user_id' => 'Id người dùng',
        ];
    }
}
