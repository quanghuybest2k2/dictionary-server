<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reviews\ReviewsRequest;
use App\Repositories\ReviewsRepositoryService\IReviewsRepository;

class ReviewController extends Controller
{
    use ResponseTrait;
    public $IReviewsRepository;

    public function __construct(IReviewsRepository $IReviewsRepository)
    {
        $this->IReviewsRepository = $IReviewsRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reviews",
     *     summary="Tạo phản hồi",
     *     description="Tạo phản hồi từ người dùng",
     *     tags={"Reviews"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              required={"user_id", "rating"},
     *             @OA\Property(property="user_id", type="integer", example=2, description="Id người dùng"),
     *             @OA\Property(property="rating", type="integer", example=5, description="1 -> 5 sao"),
     *             @OA\Property(property="comment", type="string", example="Ứng dụng tốt quá! rất ứng ý.", description="Phản hồi (có thể trống).")
     *         ),
     *     ),
     *      @OA\Response(
     *           response=200,
     *           description="Cảm ơn bạn đã đánh giá ứng dụng.",
     *           @OA\JsonContent(
     *             required={"user_id", "rating"},
     *             @OA\Property(property="user_id", type="integer", example=2, description="Id người dùng"),
     *             @OA\Property(property="rating", type="integer", example=5, description="1 -> 5 sao"),
     *             @OA\Property(property="comment", type="string", example="Ứng dụng tốt quá! rất ứng ý.", description="Phản hồi (có thể trống)."),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-27T04:58:55.000000Z"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-27T04:58:55.000000Z"),
     *             @OA\Property(property="id", type="integer", example=1)
     *           )
     *       ),
     *     @OA\Response(
     *         response=400,
     *         description="Lỗi trong quá trình xử lý request.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Đã có lỗi xảy ra!"),
     *         ),
     *     ),
     * )
     * @param \App\Http\Requests\Reviews\ReviewsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reviews(ReviewsRequest $request): JsonResponse
    {
        try {
            $data = $this->IReviewsRepository->create($request->all());
            return $data ?
                $this->responseSuccess($data, 'Cảm ơn bạn đã đánh giá ứng dụng.', Response::HTTP_CREATED)
                :
                $this->responseError('Bad request', 'Đã có lỗi xảy ra!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
