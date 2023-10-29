<?php

namespace App\Http\Requests\UserRequest;

use Illuminate\Validation\Rule;
use App\Http\Requests\FormRequest;

class UpdateRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],
            'gender' => 'required|in:1,2,3',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Vui lòng nhập :attribute',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'email.email' => 'Địa chỉ :attribute không hợp lệ.',
            'gender.in' => ':attribute không hợp lệ.',
            'email.unique' => 'Email đã tồn tại trong cơ sở dữ liệu.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Họ và tên',
            'email' => 'Email',
            'gender' => 'Giới tính',
        ];
    }
}
