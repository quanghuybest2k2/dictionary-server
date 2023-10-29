<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Requests\UserRequest\LoginRequest;
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
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Đăng ký người dùng mới",
     *     description="Đăng ký một người dùng mới bằng thông tin được gửi qua request.",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "gender", "password"},
     *             @OA\Property(property="name", type="string", example="Đoàn Quang Huy", description="Họ và tên của người dùng."),
     *             @OA\Property(property="email", type="string", format="email", example="quanghuybest@gmail.com", description="Địa chỉ email của người dùng."),
     *             @OA\Property(property="gender", type="integer", example=1, description="Giới tính của người dùng."),
     *             @OA\Property(property="password", type="string", format="password", example="12345678", description="Mật khẩu của người dùng."),
     *         ),
     *     ),
     *      @OA\Response(
     *           response=200,
     *           description="Successful register",
     *           @OA\JsonContent(
     *               @OA\Property(property="name", type="string", example="Đoàn Quang Huy"),
     *               @OA\Property(property="email", type="string", example="quanghuybest@gmail.com"),
     *               @OA\Property(property="gender", type="integer", example=1),
     *               @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-27T04:58:55.000000Z"),
     *               @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-27T04:58:55.000000Z"),
     *               @OA\Property(property="id", type="integer", example=1),
     *               @OA\Property(property="token", type="string", format="bearer", example="1|6kvnXxRVLzaZW59DMbemHEPJVqal1cVpawyqSbTU"),
     *           )
     *       ),
     *     @OA\Response(
     *         response=400,
     *         description="Lỗi trong quá trình xử lý request.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Không thành công!"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi server trong quá trình xử lý.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Lỗi server nội bộ."),
     *         ),
     *     ),
     * )
     * @param \App\Http\Requests\UserRequest\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
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

    /**
     * @OA\Post(
     *      path="/api/v1/login",
     *      operationId="loginUser",
     *      tags={"Users"},
     *      summary="User login",
     *      description="Logs the user into the application and returns an authentication token.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Login credentials",
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", format="email", example="quanghuybest@gmail.com"),
     *              @OA\Property(property="password", type="string", format="password", example="12345678")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful login",
     *          @OA\JsonContent(
     *              @OA\Property(property="userId", type="integer", example=1),
     *              @OA\Property(property="username", type="string", example="Đoàn Quang Huy"),
     *              @OA\Property(property="token", type="string", format="bearer", example="1|6kvnXxRVLzaZW59DMbemHEPJVqal1cVpawyqSbTU"),
     *              @OA\Property(property="role", type="string", example="admin"),
     *              @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-27T04:58:55.000000Z")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Thông tin không hợp lệ!")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Internal server error message.")
     *          )
     *      ),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserByEmail($request->email);

            if (!$user || !Hash::check($request->password, $user->password)) {
                //401
                return $this->responseError(null, 'Thông tin không hợp lệ!', Response::HTTP_UNAUTHORIZED);
            } else {
                if ($user->role_as == 1) { // admin
                    $role = 'admin';
                    $token = $user->createToken($user->email . '_AdminToken', ['server:admin'])->plainTextToken;
                } else {
                    $role = '';
                    $token = $user->createToken($user->email . '_Token', [''])->plainTextToken;
                }

                $data = [
                    'userId' => $user->id,
                    'username' => $user->name,
                    'token' => $token,
                    'role' => $role,
                    'created_at' => $user->created_at,
                ];
                return $this->responseSuccess($data, 'Đăng nhập thành công.');
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Đăng xuất người dùng",
     *     description="Đăng xuất người dùng hiện tại và hủy các token xác thực.",
     *     tags={"Users"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Người dùng đã đăng xuất thành công.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="null", example="null", description="Dữ liệu trả về, không có giá trị."),
     *             @OA\Property(property="message", type="string", example="Đã đăng xuất."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi server trong quá trình xử lý.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Lỗi server nội bộ."),
     *         ),
     *     ),
     *     security={
     *          {"bearer": {}}
     *      }
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            $data = $this->userRepository->deleteUserTokens(auth()->user()->id);

            return $this->responseSuccess($data, "Đã đăng xuất.");
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
     *     ),
     *     security={
     *          {"bearer": {}}
     *      }
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
