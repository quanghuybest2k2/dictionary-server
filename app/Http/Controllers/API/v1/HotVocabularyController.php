<?php

namespace App\Http\Controllers\API\v1;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\HotVocabularyRepositoryService\IHotVocabularyRepository;

class HotVocabularyController extends Controller
{
    use ResponseTrait;

    private $iHotVocabularyRepository;

    public function __construct(IHotVocabularyRepository $iHotVocabularyRepository)
    {
        $this->iHotVocabularyRepository = $iHotVocabularyRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/get-hot-vocabulary",
     *     summary="Lấy tất cả từ vựng hot",
     *     tags={"Hot Vocabulary"},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="bool", example=true),
     *             @OA\Property(property="message", type="string", example="Lấy từ vưng hot thành công!"),
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
     *         description="Không tìm thấy từ vựng hot",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Hiện tại chưa có từ vựng hot!")
     *         )
     *     )
     * )
     */
    public function getHotVocabulary()
    {
        try {
            $hotVocabulary = $this->iHotVocabularyRepository->getHotVocabulary();
            // vì get() nên không thể check $hotVocabulary > 0
            return $hotVocabulary
                ?
                $this->responseSuccess($hotVocabulary, "Lấy thành công từ vựng hot")
                :
                $this->responseError(null, "Hiện tại chưa có từ vựng! Rất xin lỗi về sự cố này.", Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
