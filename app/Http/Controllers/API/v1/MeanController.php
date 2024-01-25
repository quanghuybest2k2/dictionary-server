<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\MeanRequest\StoreMeanRequest;
use App\Repositories\MeansRepositoryService\IMeansRepository;

class MeanController extends Controller
{
    use ResponseTrait;

    private $IMeansRepository;

    public function __construct(IMeansRepository $IMeansRepository)
    {
        $this->IMeansRepository = $IMeansRepository;
    }
    /**
     * @OA\Post(
     *     path="/api/v1/store-mean",
     *     summary="Thêm nghĩa mới",
     *     description="Thêm một nghĩa mới dựa vào id từ.",
     *     tags={"Means"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"word_id", "word_type_id", "means"},
     *             @OA\Property(property="word_id", type="integer", example=1, description="Id từ"),
     *             @OA\Property(property="word_type_id", type="integer", example=2, description="Id từ loại"),
     *             @OA\Property(property="means", type="string", example="Test nghĩa", description="Nghĩa của từ."),
     *             @OA\Property(property="description", type="string", example="Test mô tả", description="Mô tả (có thể trống)."),
     *             @OA\Property(property="example", type="string", example="Test ví dụ", description="Ví dụ (có thể trống)."),
     *         ),
     *     ),
     *      @OA\Response(
     *           response=200,
     *           description="Thêm nghĩa thành công.",
     *           @OA\JsonContent(
     *             required={"word_id", "word_type_id", "means"},
     *             @OA\Property(property="word_id", type="integer", example=1, description="Id từ"),
     *             @OA\Property(property="word_type_id", type="integer", example=2, description="Id từ loại"),
     *             @OA\Property(property="means", type="string", example="Test nghĩa", description="Nghĩa của từ."),
     *             @OA\Property(property="description", type="string", example="Test mô tả", description="Mô tả (có thể trống)."),
     *             @OA\Property(property="example", type="string", example="Test ví dụ", description="Ví dụ (có thể trống)."),
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
     * @param \App\Http\Requests\MeanRequest\StoreMeanRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeMean(StoreMeanRequest $request): JsonResponse
    {
        try {
            $data = $this->IMeansRepository->createMean($request->all());
            return $data ?
                $this->responseSuccess($data, 'Thêm nghĩa thành công.')
                :
                $this->responseError(null, 'Đã có lỗi xảy ra!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
