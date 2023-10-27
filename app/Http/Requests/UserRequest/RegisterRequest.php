<?php

namespace App\Http\Requests\UserRequest;


use App\Http\Requests\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'gender' => 'in:1,2,3',
            'password' => 'required|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập :attribute',
            'name.max' => ':attribute không được vượt quá :max ký tự.',
            'email.required' => 'Vui lòng nhập địa chỉ :attribute.',
            'email.email' => 'Địa chỉ :attribute không hợp lệ.',
            'email.max' => 'Địa chỉ :attribute không được vượt quá :max ký tự.',
            'email.unique' => 'Địa chỉ :attribute đã được sử dụng.',
            'gender.in' => ':attribute không hợp lệ.',
            'password.required' => 'Vui lòng nhập :attribute.',
            'password.min' => ':attribute phải chứa ít nhất :min ký tự.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Họ và tên',
            'gender' => 'Giới tính',
            'password' => 'Mật khẩu',
        ];
    }
}
