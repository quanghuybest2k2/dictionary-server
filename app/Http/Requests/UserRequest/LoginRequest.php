<?php

namespace App\Http\Requests\UserRequest;

use App\Http\Requests\FormRequest;

class LoginRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|max:191',
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Bạn phải điền :attribute',
        ];
    }

    public function attributes(): array
    {
        return [
            'password' => 'Mật khẩu',
        ];
    }
}
