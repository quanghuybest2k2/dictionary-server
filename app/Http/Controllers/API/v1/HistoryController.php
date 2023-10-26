<?php

namespace App\Http\Controllers\API\v1;

use App\Traits\ResponseTrait;
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
    )
    {
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

    //   path="/api/v1/get-word-lookup-history/{user_id}",

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

            return $WordLookupHistory ? $this->responseSuccess($WordLookupHistory, "Lấy thành công.") : $this->responseError("Đã có lỗi xảy ra", "Lấy thất bại!");
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ====================== TranslateHistory =============================
    public function loadTranslateHistoryByUser($user_id)
    {
        try {
            $translateHistory = $this->historiesRepository->loadAllTranslateHistory($user_id);

            if ($translateHistory) {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'translateHistory' => $translateHistory
                ]);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'error' => 'Lấy thất bại!'
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Throwable $th) {
            throw $th;
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

    // delete all record
    public function destroy(Request $request)
    {
        try {
            $isSuccess = $this->historiesRepository->deleteAllTranslateHistory($request->user_id);
            if ($isSuccess) {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Đã xóa toàn bộ bản dịch.'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Không tìm thấy bản dịch'
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroyById(Request $request)
    {
        try {
            $isDelete = $this->historiesRepository->deleteByIdTranslateHistory($request->user_id, $request->id);
            if ($isDelete) {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Đã xóa bản dịch.'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Không tìm thấy bản dịch'
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
