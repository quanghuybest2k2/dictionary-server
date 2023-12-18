<?php

namespace App\Http\Controllers\API\v1;

use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\WordLookupHistory;
use App\Http\Controllers\Controller;
use App\Models\LoveText;
use App\Models\TranslateHistory;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\HistoriesRepositoryService\IHistoriesRepository;

class HistoryController extends Controller
{
    use ResponseTrait;

    private $historiesRepository;

    public function __construct(
        IHistoriesRepository $historiesRepository,
    ) {
        $this->historiesRepository = $historiesRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/check-if-exist",
     *      tags={"History"},
     *      summary="Check if a word or love text exists in history",
     *      description="Check if a word or love text exists in the user's history by English keyword and user ID",
     *     @OA\Parameter(
     *          name="english",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          description="English"
     *      ),
     *       @OA\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *          description="User Id"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200)
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="validator_errors", type="object", example={"english": {"Vui lòng nhập Từ khóa tiếng anh"}, "user_id": {"Id người dùng phải là số nguyên dương."}})
     *          )
     *      ),
     * )
     */
    public function checkIfExist(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'english' => 'required|max:400',
                    'user_id' => 'required|integer|min:1',
                ],
                [
                    'required' => 'Vui lòng nhập :attribute.',
                    'max' => ':attribute không được vượt quá :max ký tự.',
                    'user_id.integer' => ':attribute phải là số nguyên dương.',
                ],
                [
                    'english' => 'Từ khóa tiếng anh',
                    'user_id' => 'Id người dùng',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'validator_errors' => $validator->messages(),
                ]);
            } else {
                $word = $this->historiesRepository->checkIfExist(new WordLookupHistory(), $request->english, $request->user_id);
                $loveText = $this->historiesRepository->checkIfExist(new LoveText(), $request->english, $request->user_id);

                return $this->responseSuccess(['word' => $word, 'loveText' => $loveText], "Kiểm tra thành công.");
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ====================== WordLookupHistory ============================
    public function storeWordLookupHistory(Request $request)
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

            $wordLookupHistory = $this->historiesRepository->createWordLookupHistory($request->all());

            if ($wordLookupHistory) {
                return response()->json([
                    'status' => Response::HTTP_CREATED,
                    'message' => 'Đã thêm từ này vào lịch sử.',
                    'wordLookup' => $wordLookupHistory
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
     * @OA\Get(
     *     path="/api/v1/get-word-lookup-history/{user_id}",
     *     summary="Lấy từ vựng trong lịch sử tra từ theo id người dùng",
     *     tags={"History"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="Nhập id của người dùng",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thành công từ vựng trong lịch sử tra từ.",
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
     *         description="Không tìm thấy người id dùng!",
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
    public function getWordLookupHistory($user_id)
    {
        try {
            $WordLookupHistory = $this->historiesRepository->getWordLookupHistory($user_id);

            return $WordLookupHistory
                ?
                $this->responseSuccess($WordLookupHistory, "Lấy thành công.")
                :
                $this->responseError("Đã có lỗi xảy ra", "Lấy thất bại!");
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/v1/display-by-time-word-lookup-history",
     *      tags={"History"},
     *      summary="Display by Time Word Lookup",
     *      description="Search for word lookup by user id and english",
     *      @OA\Parameter(name="user_id", in="query", required=true, @OA\Schema(type="integer"), description="User id"),
     *      @OA\Parameter(name="time", in="query", required=true, @OA\Schema(type="string"), description="day/month/year", example="18/12/2023"),
     *      @OA\Response(response=200, description="Successful operation", @OA\JsonContent(@OA\Property(property="status", type="integer", example=200))),
     *      @OA\Response(response=404, description="Not found", @OA\JsonContent(@OA\Property(property="status", type="integer", example=404), @OA\Property(property="error", type="string", example="Không tìm thấy tài nguyên!"))),
     *      @OA\Response(response=422, description="Validation error", @OA\JsonContent(@OA\Property(property="validator_errors", type="object", example={"time": {"Vui lòng nhập ngày giờ"}, "user_id": {"User id phải là số!"}})))
     * )
     */
    public function displayByTimeWordLookupHistory(Request $request): JsonResponse
    {
        try {
            $data = $this->historiesRepository->displayByTimeWordLookupHistory($request->user_id, $request->time);
            if ($data) {
                return $this->responseSuccess($data, 'Thành công');
            } else {
                return $this->responseError(null, 'Không tìm thấy tài nguyên!', Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/v1/display-by-time-translate-history",
     *      tags={"History"},
     *      summary="Display by Time Translate History",
     *      description="Search for translate by a user id and english",
     *      @OA\Parameter(name="user_id", in="query", required=true, @OA\Schema(type="integer"), description="User id"),
     *      @OA\Parameter(name="time", in="query", required=true, @OA\Schema(type="string"), description="day/month/year", example="18/12/2023"),
     *      @OA\Response(response=200, description="Successful operation", @OA\JsonContent(@OA\Property(property="status", type="integer", example=200))),
     *      @OA\Response(response=404, description="Not found", @OA\JsonContent(@OA\Property(property="status", type="integer", example=404), @OA\Property(property="error", type="string", example="Không tìm thấy tài nguyên!"))),
     *      @OA\Response(response=422, description="Validation error", @OA\JsonContent(@OA\Property(property="validator_errors", type="object", example={"time": {"Vui lòng nhập ngày giờ"}, "user_id": {"User id phải là số!"}})))
     * )
     */
    public function displayByTimeTranslateHistory(Request $request): JsonResponse
    {
        try {
            $data = $this->historiesRepository->displayByTimeTranslateHistory($request->user_id, $request->time);
            if ($data) {
                return $this->responseSuccess($data, 'Thành công');
            } else {
                return $this->responseError(null, 'Không tìm thấy tài nguyên!', Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/v1/delete-by-id-word-lookup-history/{user_id}/{id}",
     *     tags={"History"},
     *     security={{"bearer":{}}},
     *     summary="Delete By Id Word Lookup History",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Word id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy tài nguyên",
     *     ),
     *     security={
     *          {"bearer": {}}
     *      }
     * )
     */
    public function deleteByIdWordLookupHistory(Request $request)
    {
        try {
            $data = $this->historiesRepository->deleteByIdWordLookupHistory($request->user_id, $request->id);
            if ($data) {
                return $this->responseSuccess($data, 'Đã xóa từ vựng này trong lịch sử.');
            } else {
                return $this->responseError(null, 'Không tìm thấy tài nguyên!', Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    // delete Word Lookup History
    /**
     * @OA\Delete(
     *     path="/api/v1/delete-word-lookup-history/{user_id}",
     *     tags={"History"},
     *     security={{"bearer":{}}},
     *     summary="Delete All Word Lookup History",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy tài nguyên",
     *     ),
     *     security={
     *          {"bearer": {}}
     *      }
     * )
     */
    public function deleteAllWordLookupHistory(Request $request)
    {
        try {
            $data = $this->historiesRepository->deleteAllWordLookupHistory($request->user_id);
            if ($data) {
                return $this->responseSuccess($data, 'Đã xóa toàn bộ bản dịch của người này trong lịch sử.');
            } else {
                return $this->responseError(null, 'Không tìm thấy tài nguyên!', Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ====================== TranslateHistory =============================

    /**
     * @OA\Get(
     *     path="/api/v1/get-translate-history/{user_id}",
     *     summary="Lấy bản dịch trong lịch sử dịch theo id người dùng",
     *     tags={"History"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="Nhập id của người dùng",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thành công bản dịch trong lịch sử tra từ.",
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
     *         description="Không tìm thấy người id dùng!",
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
    public function loadTranslateHistoryByUser($user_id)
    {
        try {
            $translateHistory = $this->historiesRepository->loadAllTranslateHistory($user_id);

            return $translateHistory
                ?
                $this->responseSuccess($translateHistory, "Lấy thành công.")
                :
                $this->responseError("Đã có lỗi xảy ra", "Lấy thất bại!");
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeTranslateHistory(Request $request)
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

            $translateHistory = $this->historiesRepository->createTranslateHistory($request->all());
            // $existingRecord = TranslateHistory::where('english', $request->english)
            //     ->where('vietnamese', $request->vietnamese)
            //     ->where('user_id', $request->user_id)
            //     ->first();

            if ($translateHistory) {
                return response()->json([
                    'status' => Response::HTTP_CREATED,
                    'message' => 'Đã thêm bản dịch này vào lịch sử.',
                    'wordLookup' => $translateHistory
                ], Response::HTTP_CREATED);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Thêm thất bại!'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    // delete All Translate History
    /**
     * @OA\Delete(
     *     path="/api/v1/delete-translate-history/{user_id}",
     *     tags={"History"},
     *     security={{"bearer":{}}},
     *     summary="Delete All Translate History",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy tài nguyên",
     *     ),
     *     security={
     *          {"bearer": {}}
     *      }
     * )
     */
    public function deleteAllTranslateHistory(Request $request)
    {
        try {
            $data = $this->historiesRepository->deleteAllTranslateHistory($request->user_id);
            if ($data) {
                return $this->responseSuccess($data, 'Đã xóa toàn bộ bản dịch của người dùng này trong lịch sử.');
            } else {
                return $this->responseError(null, 'Không tìm thấy tài nguyên!', Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/v1/delete-translate-by-id/{user_id}/{id}",
     *     tags={"History"},
     *     security={{"bearer":{}}},
     *     summary="Delete By Id Translate History",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Translate id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy tài nguyên",
     *     ),
     *     security={
     *          {"bearer": {}}
     *      }
     * )
     */
    public function deleteByIdTranslateHistory(Request $request)
    {
        try {
            $data = $this->historiesRepository->deleteByIdTranslateHistory($request->user_id, $request->id);
            if ($data) {
                return $this->responseSuccess($data, 'Đã xóa bản dịch này trong lịch sử.');
            } else {
                return $this->responseError(null, 'Không tìm thấy tài nguyên!', Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/v1/delete-all-history/{user_id}",
     *     tags={"History"},
     *     security={{"bearer":{}}},
     *     summary="Delete All History",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy tài nguyên",
     *     ),
     *     security={
     *          {"bearer": {}}
     *      }
     * )
     */
    public function deleteAllHistory(Request $request)
    {
        try {
            $data = $this->historiesRepository->deleteAllHistory($request->user_id);
            if ($data) {
                return $this->responseSuccess($data, 'Đã xóa tất cả lịch sử của người dùng này.');
            } else {
                return $this->responseError(null, 'Không tìm thấy tài nguyên!', Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
