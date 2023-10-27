<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Requests\UserRequest\RegisterRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\UserRepositoryService\IUserRepository;

class UserController extends Controller
{
    use ResponseTrait;

    private $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\POST(
     *     path="/api/v1/register",
     *     tags={"Users"},
     *     summary="Register User",
     *     description="Register New User",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Đoàn Quang Huy"),
     *              @OA\Property(property="email", type="string", example="quanghuybest@gmail.com"),
     *              @OA\Property(property="gender", type="integer", example=1),
     *              @OA\Property(property="password", type="string", example="12345678")
     *          ),
     *      ),
     *      @OA\Response(response=200, description="Register New User Data" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $requestData = $request->only('name', 'email', 'gender', 'password');
            $user = $this->userRepository->createUser($requestData);

            if (!$user) {
                return $this->responseError(null, "Không thành công!");
            }

            return $this->responseSuccess($user, 'Đăng ký thành công.', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
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
                'required' => 'Bạn phải điền :attribute',
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

    /**
     * @OA\Get(
     *     path="/api/v1/get-user/{id}",
     *     summary="Lấy người dùng theo id",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Nhập id của người dùng",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thành công thông tin người dùng.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy người dùng!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=404
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Lỗi rồi"
     *             )
     *         )
     *     )
     * )
     */
    public function getUser($id)
    {
        try {
            $user = $this->userRepository->getUserById($id);
            return $user ?
                $this->responseSuccess($user, 'Lấy thành công user!')
                :
                $this->responseError(null, 'Không tìm thấy user!', Response::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return $isDelete ?
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
