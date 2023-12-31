<?php

namespace App\Http\Controllers\API\v1;

use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Repositories\MiniGameRepositoryService\IMiniGameRepository;
use Illuminate\Http\JsonResponse;

class MiniGameController extends Controller
{
    use ResponseTrait;

    private $iMiniGameRepository;

    public function __construct(
        IMiniGameRepository $iMiniGameRepository,
    ) {
        $this->iMiniGameRepository = $iMiniGameRepository;
    }
    /**
     * @OA\Get(
     *     path="/api/v1/get-questions/{limit}/{user_id}",
     *     summary="lấy câu hỏi",
     *     tags={"MiniGame"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="path",
     *         required=true,
     *         description="Nhập số lượng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="Nhập id của người dùng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy câu hỏi thành công.",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="integer", example=200))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Lấy câu hỏi không thành công!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="error", type="string", example="Lấy câu hỏi không thành công!")
     *         )
     *     )
     * )
     */
    public function getQuestions($limit, $user_id): JsonResponse
    {
        try {
            $data = $this->iMiniGameRepository->getQuestions($limit, $user_id);

            return $data
                ?
                $this->responseSuccess($data, "Lấy câu hỏi thành công.")
                :
                $this->responseError(null, "Lấy câu hỏi không thành công!");
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/v1/get-more-questions-mini-game/{limit}",
     *     summary="lấy thêm đáp án theo tổng số lượng",
     *     tags={"MiniGame"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="path",
     *         required=true,
     *         description="Nhập số lượng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thêm câu hỏi thành công.",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="integer", example=200))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Lấy không thành công!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="error", type="string", example="Lấy không thành công!")
     *         )
     *     )
     * )
     */
    public function getMoreQuestionsMiniGame($limit): JsonResponse
    {
        try {
            $data = $this->iMiniGameRepository->getMoreQuestions($limit);

            return $data
                ?
                $this->responseSuccess($data, "Lấy thêm câu hỏi thành công.")
                :
                $this->responseError(null, "Lấy không thành công!");
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/v1/get-random-wrong-answers/{english}/{limit}",
     *     summary="lấy đáp án sai",
     *     tags={"MiniGame"},
     *     @OA\Parameter(
     *         name="english",
     *         in="path",
     *         required=true,
     *         description="Nhập tên từ loại ra",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="path",
     *         required=true,
     *         description="Nhập số lượng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy đáp án sai thành công.",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="integer", example=200))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Lấy đáp án không thành công!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="error", type="string", example="Lấy đáp án sai không thành công!")
     *         )
     *     )
     * )
     */
    public function getRandomWrongAnswers($english, $limit): JsonResponse
    {
        try {
            $data = $this->iMiniGameRepository->getRandomWrongAnswers($english, $limit);

            return $data
                ?
                $this->responseSuccess($data, "Lấy đáp án sai thành công.")
                :
                $this->responseError(null, "Lấy đáp án sai không thành công!");
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
