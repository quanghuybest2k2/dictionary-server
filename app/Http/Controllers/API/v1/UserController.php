<?php

namespace App\Http\Controllers\API\v1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UserRequest\RegisterRequest;
use App\Repositories\UserRepositoryService\IUserRepository;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:191',
                'email' => 'required|email|max:191|unique:users,email',
                'gender' => 'in:1,2,3',
                'password' => 'required|min:8',
            ],
            [
                'name.required' => 'Vui lòng nhập :attribute',
                'name.max' => ':attribute không được vượt quá :max ký tự.',
                'email.required' => 'Vui lòng nhập địa chỉ :attribute.',
                'email.email' => 'Địa chỉ :attribute không hợp lệ.',
                'email.max' => 'Địa chỉ :attribute không được vượt quá :max ký tự.',
                'email.unique' => 'Địa chỉ :attribute đã được sử dụng.',
                'gender.in' => ':attribute không hợp lệ.',
                'password.required' => 'Vui lòng nhập :attribute.',
                'password.min' => ':attribute phải chứa ít nhất :min ký tự.',
            ],
            [
                'name' => 'Họ và tên',
                'gender' => 'Giới tính',
                'password' => 'Mật khẩu',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'validator_errors' => $validator->messages(),
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken($user->email . '_Token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'name' => $user->name,
                'email' => $user->email,
                'gender' => $user->gender,
                'token' => $token,
                'message' => 'Đăng ký thành công.',
            ], Response::HTTP_OK);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|max:191',
                'password' => 'required',
            ],
            [
                'required'  => 'Bạn phải điền :attribute',
            ],
            [
                'password' => 'Mật khẩu',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'validator_errors' => $validator->messages(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        } else {
            $user = $this->userRepository->getUserByEmail($request->email);

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Thông tin không hợp lệ!',
                ], Response::HTTP_UNAUTHORIZED); // 401
            } else {
                if ($user->role_as == 1) { // admin
                    $role = 'admin';
                    $token = $user->createToken($user->email . '_AdminToken', ['server:admin'])->plainTextToken;
                } else {
                    $role = '';
                    $token = $user->createToken($user->email . '_Token', [''])->plainTextToken;
                }
                return response()->json([
                    'status' => 200,
                    'userId' => $user->id,
                    'username' => $user->name,
                    'token' => $token,
                    'message' => 'Đăng nhập thành công.',
                    'role' => $role,
                    'created_at' => $user->created_at,
                ]);
            }
        }
    }
    public function logout()
    {
        $this->userRepository->deleteUserTokens(auth()->user()->id);

        return response()->json([
            'status' => 200,
            'message' => 'Đã đăng xuất.',
        ]);
    }
    public function getUser($id)
    {
        try {
            $user = $this->userRepository->getUserById($id);
            return  $user ?
                response()->json([
                    'status' => Response::HTTP_OK,
                    'user' => $user
                ], Response::HTTP_OK)
                :
                response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Không tìm thấy người dùng!'
                ], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function update($id, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($id),
                ],
                'gender' => 'in:1,2,3',
            ],
            [
                'required' => 'Vui lòng nhập :attribute',
                'max' => ':attribute không được vượt quá :max ký tự.',
                'email.email' => 'Địa chỉ :attribute không hợp lệ.',
                'gender.in' => ':attribute không hợp lệ.',
                'email.unique' => 'Email đã tồn tại trong cơ sở dữ liệu.',
            ],
            [
                'name' => 'Họ và tên',
                'email' => 'Email',
                'gender' => 'Giới tính',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'validator_errors' => $validator->messages(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {

            $user = $this->userRepository->UpdateUser($id, $request->all());

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Cập nhật thông tin thành công.',
                'user' => $user,
            ], Response::HTTP_OK);
        }
    }
    public function destroyUser($id)
    {
        try {
            $isDelete = $this->userRepository->deleteUser($id);
            return  $isDelete ?
                response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Tài khoản của bản đã được xóa vĩnh viễn.'
                ], Response::HTTP_OK)
                :
                response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Không tìm thấy người dùng!'
                ], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
