<?php

namespace App\Http\Requests\SearchRequest;

use App\Http\Requests\FormRequest;

class SearchBySpecialtyRequest extends FormRequest
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
            'searched_word' => 'required',
            'specialization_id' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Vui lòng nhập :attribute',
            'specialization_id.integer' => 'Id của chuyên ngành phải là số!',
        ];
    }

    public function attributes(): array
    {
        return [
            'searched_word' => 'Từ vựng cần tìm',
            'specialization_id' => 'Id của chuyên ngành',
        ];
    }
}
