<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoveRequest\UpdateRequest;
use Illuminate\Support\Facades\Validator;
use App\Repositories\LoveRepositoryService\ILoveRepository;

class LoveController extends Controller
{
    use ResponseTrait;

    private $iLoveRepository;

    public function __construct(
        ILoveRepository $iLoveRepository,
    ) {
        $this->iLoveRepository = $iLoveRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/total-love-item/{user_id}",
     *     summary="Tổng sô từ vựng của 2 loại",
     *     tags={"Favorite"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="Nhập id của người dùng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thành công.",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="integer", example=200))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy id người dùng!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Lỗi rồi")
     *         )
     *     )
     * )
     */
    public function TotalLoveItemOfUser($user_id): JsonResponse
    {
        try {
            $totalLoveItem = $this->iLoveRepository->TotalLoveItemOfUser($user_id);

            return $totalLoveItem > 0
                ?
                $this->responseSuccess($totalLoveItem, "Lấy thành công.")
                :
                $this->responseSuccess(0, "Lấy thành công.");
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/v1/delete-all-favorite/{user_id}",
     *     tags={"Favorite"},
     *     security={{"bearer":{}}},
     *     summary="Xóa toàn bộ mục yêu thích trong tài khoản cụ thể",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(response=200, description="Đã xóa toàn bộ mục yêu thích tài khoản này."),
     *     @OA\Response(response=204, description="Không thể xóa đánh dấu yêu thích!"),
     *     security={{"bearer": {}}}
     * )
     */
    public function destroyAllFavorite($user_id): JsonResponse
    {
        try {
            $isDelete = $this->iLoveRepository->deleteAllFavorite($user_id);

            if (!$isDelete) {
                return $this->responseError('NO CONTENT', 'Không thể xóa đánh dấu yêu thích!', Response::HTTP_NO_CONTENT);
            }
            return $this->responseSuccess($isDelete, 'Đã xóa toàn bộ mục yêu thích tài khoản này.', Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    // ================================== Từ vựng ========================================
    /**
     * @OA\Get(
     *     path="/api/v1/show-love-vocabulary/{user_id}",
     *     summary="Hiển thị từ vựng yêu thích",
     *     tags={"Favorite"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="Nhập id của người dùng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thành công.",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="integer", example=200))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy id người dùng!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Lỗi rồi")
     *         )
     *     )
     * )
     */
    public function showLoveVocabulary($user_id): JsonResponse
    {
        try {
            $loveVocabulary = $this->iLoveRepository->displayLoveVocabulary($user_id);

            return $loveVocabulary
                ?
                $this->responseSuccess($loveVocabulary, "Lấy thành công.")
                :
                $this->responseError("Lỗi rồi!");
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function saveLoveVocabulary(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'english' => 'required|max:400',
                'pronunciations' => 'required|max:100',
                'vietnamese' => 'required|max:400',
                'user_id' => 'required|integer|min:1',
            ],
            [
                'required' => 'Vui lòng nhập :attribute.',
                'max' => ':attribute không được vượt quá :max ký tự.',
                'integer' => ':attribute phải là số nguyên.',
                'min' => ':attribute phải lớn hơn hoặc bằng :min.',
            ],
            [
                'english' => 'Tiếng anh',
                'pronunciations' => 'Phiên âm',
                'vietnamese' => 'Tiếng việt',
                'user_id' => 'Id người dùng',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'validator_errors' => $validator->messages(),
            ]);
        } else {

            $loveVocabularies = $this->iLoveRepository->createLoveVocabulary($request->all());

            if ($loveVocabularies) {
                return response()->json([
                    'status' => Response::HTTP_CREATED,
                    'message' => 'Đã thêm từ này vào mục yêu thích.',
                    'loveVocabularies' => $loveVocabularies
                ], Response::HTTP_CREATED);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Thêm thất bại!'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/v1/delete-love-vocabulary/{english}/{user_id}",
     *     tags={"Favorite"},
     *     security={{"bearer":{}}},
     *     summary="Xóa từ vựng yêu thích",
     *     @OA\Parameter(
     *         name="english",
     *         in="path",
     *         description="Tiếng anh",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(response=400, description="Invalid ID supplied"),
     *     @OA\Response(response=404, description="Không tìm thấy tài nguyên"),
     *     security={{"bearer": {}}}
     * )
     */
    public function destroyLoveVocabulary($english, $user_id): JsonResponse
    {
        try {
            $isDelete = $this->iLoveRepository->deleteLoveVocabulary($english, $user_id);

            if (!$isDelete) {
                return $this->responseError('NO CONTENT', 'Không thể xóa đánh dấu yêu thích!', Response::HTTP_NO_CONTENT);
            }
            return $this->responseSuccess($isDelete, 'Đã xóa đánh dấu yêu thích.', Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/v1/sort-by-favorite-word-lookup/{user_id}",
     *     summary="Sắp xếp từ vựng yêu thích",
     *     tags={"Favorite"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="Nhập id của người dùng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sắp xếp thành công.",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="integer", example=200))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Đã có lỗi xảy ra!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="error", type="string", example="Đã có lỗi xảy ra!")
     *         )
     *     )
     * )
     */
    public function sortByFavoriteWordLookup($user_id): JsonResponse
    {
        try {
            $sortResult = $this->iLoveRepository->sortByVocabulary($user_id);

            if (!$sortResult) {
                return $this->responseError('Bad request', 'Đã có lỗi xảy ra!');
            }
            return $this->responseSuccess($sortResult, 'Sắp xếp thành công.', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\PUT(
     *     path="/api/v1/update-favorite-vocabulary/{id}/{user_id}",
     *     tags={"Favorite"},
     *     summary="Cập nhật ghi chú",
     *     description="Cập nhật ghi chú từ vựng",
     *     @OA\Parameter(name="id", description="id vd: 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="user_id", description="user_id vd: 2", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="Note", type="string", example="Tường lửa là một hệ thống bảo mật")
     *          ),
     *      ),
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200, description="Cập nhật ghi chú từ vựng thành công."),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Không tìm thấy tài nguyên!"),
     * )
     */
    public function updateFavoriteVocabulary($id, $user_id, UpdateRequest $request): JsonResponse
    {
        try {
            $data = $this->iLoveRepository->updateVocabulary($id, $user_id, $request->Note);

            if (is_null($data)) {
                return $this->responseError(null, 'Không tìm thấy tài nguyên!', Response::HTTP_NOT_FOUND);
            }
            return $this->responseSuccess($data, 'Cập nhật ghi chú từ vựng thành công.', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    // ================================== Dịch ========================================
    /**
     * @OA\Get(
     *     path="/api/v1/show-love-text/{user_id}",
     *     summary="Hiển thị bản dịch yêu thích",
     *     tags={"Favorite"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="Nhập id của người dùng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thành công.",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="integer", example=200))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy id người dùng!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Lỗi rồi")
     *         )
     *     )
     * )
     */
    public function showLoveText($user_id): JsonResponse
    {
        try {
            $loveText = $this->iLoveRepository->displayLoveText($user_id);

            return $loveText
                ?
                $this->responseSuccess($loveText, "Lấy thành công.")
                :
                $this->responseError("Lỗi rồi!");
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function saveLoveText(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'english' => 'required|max:400',
                'vietnamese' => 'required|max:400',
                'user_id' => 'required|integer|min:1',
            ],
            [
                'required' => 'Vui lòng nhập :attribute.',
                'max' => ':attribute không được vượt quá :max ký tự.',
                'integer' => ':attribute phải là số nguyên.',
                'min' => ':attribute phải lớn hơn hoặc bằng :min.',
            ],
            [
                'english' => 'Tiếng anh',
                'vietnamese' => 'Tiếng việt',
                'user_id' => 'Id người dùng',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'validator_errors' => $validator->messages(),
            ]);
        } else {

            $loveTexts = $this->iLoveRepository->createLoveTexts($request->all());

            if ($loveTexts) {
                return response()->json([
                    'status' => Response::HTTP_CREATED,
                    'message' => 'Đã thêm bản dịch này vào mục yêu thích.',
                    'loveTexts' => $loveTexts
                ], Response::HTTP_CREATED);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Thêm thất bại!'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/v1/delete-love-text",
     *     tags={"Favorite"},
     *     summary="Xóa đánh dấu yêu thích",
     *     description="Xóa đánh dấu yêu thích cho một từ vựng",
     *     operationId="destroyLoveText",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Thông tin cần thiết để xóa đánh dấu yêu thích",
     *         @OA\JsonContent(
     *             required={"english", "user_id"},
     *             @OA\Property(property="english", type="string", description="Tiếng Anh của từ vựng"),
     *             @OA\Property(property="user_id", type="integer", description="ID của người dùng"),
     *         ),
     *     ),
     *     @OA\Response(response=204, description="Xóa đánh dấu yêu thích thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Không tìm thấy tài nguyên"),
     * )
     */
    public function destroyLoveText(Request $request): JsonResponse
    {
        try {
            $isDelete = $this->iLoveRepository->deleteLoveText($request->english, $request->user_id);

            if (!$isDelete) {
                return $this->responseError('NO CONTENT', 'Không thể xóa đánh dấu yêu thích!', Response::HTTP_NO_CONTENT);
            }
            return $this->responseSuccess($isDelete, 'Đã xóa đánh dấu yêu thích.', Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/v1/sort-by-favorite-text/{user_id}",
     *     summary="Sắp xếp bản dịch yêu thích",
     *     tags={"Favorite"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="Nhập id của người dùng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sắp xếp thành công.",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="integer", example=200))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Đã có lỗi xảy ra!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="error", type="string", example="Đã có lỗi xảy ra!")
     *         )
     *     )
     * )
     */
    public function sortByFavoriteText($user_id): JsonResponse
    {
        try {
            $sortResult = $this->iLoveRepository->sortByText($user_id);

            if (!$sortResult) {
                return $this->responseError('Bad request', 'Đã có lỗi xảy ra!');
            }
            return $this->responseSuccess($sortResult, 'Sắp xếp thành công.', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\PUT(
     *     path="/api/v1/update-favorite-text/{id}/{user_id}",
     *     tags={"Favorite"},
     *     summary="Cập nhật ghi chú",
     *     description="Cập nhật ghi chú bản dịch",
     *     @OA\Parameter(name="id", description="id vd: 2", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="user_id", description="user_id vd: 3", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="Note", type="string", example="Tom là anh của David")
     *          ),
     *      ),
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200, description="Cập nhật ghi chú bản dịch thành công."),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Không tìm thấy tài nguyên!"),
     * )
     */
    public function updateFavoriteText($id, $user_id, UpdateRequest $request): JsonResponse
    {
        try {
            $data = $this->iLoveRepository->updateText($id, $user_id, $request->Note);

            if (is_null($data)) {
                return $this->responseError(null, 'Không tìm thấy tài nguyên!', Response::HTTP_NOT_FOUND);
            }
            return $this->responseSuccess($data, 'Cập nhật ghi chú bản dịch thành công.', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
