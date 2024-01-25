<?php

namespace App\Http\Requests\HistoryRequest;

use App\Http\Requests\FormRequest;

class storeTranslateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'english' => 'required|max:400',
            'vietnamese' => 'required|max:400',
            'user_id' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Vui lòng nhập :attribute.',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'integer' => ':attribute phải là số nguyên.',
            'min' => ':attribute phải lớn hơn hoặc bằng :min.',
        ];
    }

    public function attributes(): array
    {
        return [
            'english' => 'Tiếng anh',
            'vietnamese' => 'Tiếng việt',
            'user_id' => 'Id người dùng',
        ];
    }
}
