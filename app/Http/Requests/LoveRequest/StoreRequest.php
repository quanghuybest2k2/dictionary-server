<?php

namespace App\Http\Requests\LoveRequest;

use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    protected $requestType;

    public function setRequestType($type)
    {
        $this->requestType = $type;
    }
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
        $pronunciationsRule = ($this->requestType === 'loveVocabulary') ? 'required|max:100' : 'max:100';

        return [
            'english' => 'required|max:400',
            'pronunciations' => $pronunciationsRule,
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
            'pronunciations' => 'Phiên âm',
            'vietnamese' => 'Tiếng việt',
            'user_id' => 'Id người dùng',
        ];
    }
}
