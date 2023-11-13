<?php

namespace App\Http\Requests\SearchRequest;

use App\Http\Requests\FormRequest;


class SearchHistoryRequest extends FormRequest
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
            'english' => 'required',
            'user_id' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Vui lòng nhập :attribute.',
            'integer' => ':attribute phải là số nguyên.'
        ];
    }

    public function attributes(): array
    {
        return [
            'english' => 'Từ tiếng anh',
            'user_id' => 'Id người dùng'
        ];
    }
}
