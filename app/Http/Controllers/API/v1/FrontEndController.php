<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\WordRepositoryService\IWordRepository;

class FrontEndController extends Controller
{
    use ResponseTrait;

    private $wordRepository;

    public function __construct(
        IWordRepository $wordRepository,
    ) {
        $this->wordRepository = $wordRepository;
    }
    /**
     * @OA\Get(
     *     path="/api/v1/get-suggest-all",
     *     summary="Lấy tất cả từ gợi ý khi tìm kiếm",
     *     tags={"Get Suggest"},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="bool", example=true),
     *             @OA\Property(property="message", type="string", example="Lấy từ tất cả từ vưng thành công !"),
     *            @OA\Property(
     *          property="data",
     *          type="array",
     *          @OA\Items(
     *              type="string",
     *              example="Firewall"
     *              )
     *          )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy từ gợi ý",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Hiện tại chưa có gợi ý!")
     *         )
     *     )
     * )
     */
    // gợi ý từ
    public function suggest_all()
    {
        // chỉ lấy cột word_name của các record thôi
        try {
            $data = $this->wordRepository->getAll(); //->pluck('word_name')
            if ($data->isEmpty()) {
                return $this->responseError(null, 'Hiện tại chưa có gợi ý!', Response::HTTP_NOT_FOUND);
            }
            return $this->responseSuccess($data, 'Lấy từ tất cả từ vưng thành công !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/get-suggest",
     *     summary="Lấy danh sách các từ gợi ý theo chuyên ngành cụ thể",
     *     tags={"Get Suggest"},
     *     @OA\Parameter(
     *         name="specialization_id",
     *         in="query",
     *         required=true,
     *         description="Nhập id của chuyên ngành",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách các từ gợi ý theo chuyên ngành",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="suggest_name",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string",
     *                     example="Ví dụ: 1"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy từ gợi ý",
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
    // gợi ý theo từ vựng theo chuyên ngành
    public function suggest(Request $request)
    {
        try {
            $data = $this->wordRepository->getSuggest($request->specialization_id);
            if ($data->isEmpty()) {
                return $this->responseError(null, 'Hiện tại chưa có gợi ý!', Response::HTTP_NOT_FOUND);
            }
            return $this->responseSuccess($data, 'Lấy từ tất cả từ vưng thành công !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
