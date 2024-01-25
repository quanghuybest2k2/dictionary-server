<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use App\Models\Means;
use App\Models\WordType;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\Specialization;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\WordRequest\StoreWordRequest;
use App\Repositories\WordRepositoryService\IWordRepository;
use App\Repositories\MeansRepositoryService\IMeansRepository;
use App\Repositories\WordTypeRepositoryService\IWordTypeRepository;
use App\Repositories\SpecializationRepositoryService\ISpecializationRepository;

class WordController extends Controller
{
    use ResponseTrait;

    private $wordRepository;
    private $specializationRepository;
    private $meansRepository;
    private $wordTypeRepository;

    public function __construct(
        IWordRepository           $wordRepository,
        ISpecializationRepository $specializationRepository,
        IMeansRepository          $meansRepository,
        IWordTypeRepository       $wordTypeRepository
    ) {
        $this->wordRepository = $wordRepository;
        $this->specializationRepository = $specializationRepository;
        $this->meansRepository = $meansRepository;
        $this->wordTypeRepository = $wordTypeRepository;
    }

    /**
     * word_name
     * type_name
     * pronunciations
     * specialization_name
     * means
     * description
     * example
     * synonymous
     * antonyms
     */
    /**
     * @OA\Get(
     *     path="/api/v1/random-word",
     *     summary="Lấy từ vựng ngẫu nhiên",
     *     tags={"Words"},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm từ vựng",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Hiện tại chưa có từ vựng!")
     *         )
     *     )
     * )
     */
    public function getRandomWord()
    {
        try {
            $randomWord = $this->wordRepository->getRandomWord();
            return $randomWord ?
                $this->responseSuccess($randomWord, 'Lấy thành công từ ngẫu nhiên!')
                :
                $this->responseError(null, 'Không tìm thấy từ vựng!', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/v1/get-all-word",
     *     summary="Lấy từ vựng",
     *     tags={"Words"},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy từ thành công.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy từ vựng!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy từ vựng!")
     *         )
     *     )
     * )
     */
    public function getAllWord()
    {
        try {
            $data = $this->wordRepository->getAll();
            return $data ?
                $this->responseSuccess($data, 'Lấy từ thành công.')
                :
                $this->responseError(null, 'Không tìm thấy từ vựng!', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/v1/get-unapproved",
     *     summary="Lấy từ vựng chưa kiểm duyệt",
     *     tags={"Words"},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy từ thành công.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy từ vựng!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy từ vựng!")
     *         )
     *     )
     * )
     */
    public function getUnApproved()
    {
        try {
            $data = $this->wordRepository->getUnApproved();
            return $data ?
                $this->responseSuccess($data, 'Lấy từ thành công.')
                :
                $this->responseError(null, 'Không tìm thấy từ vựng!', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/v1/store-word",
     *     summary="Thêm từ vựng mới",
     *     description="Thêm một từ mới khi người dùng nhập vào các input.",
     *     tags={"Words"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"word_name", "specialization_id", "synonymous", "antonyms"},
     *             @OA\Property(property="word_name", type="string", example="Constant", description="Tên từ vựng"),
     *             @OA\Property(property="specialization_id", type="integer", example=2, description="Id chuyên ngành."),
     *             @OA\Property(property="synonymous", type="string", example="abc", description="Từ đồng nghĩa (có thể trống)."),
     *             @OA\Property(property="antonyms", type="string", example="xyz", description="Từ trái nghĩa (có thể trống)."),
     *         ),
     *     ),
     *      @OA\Response(
     *           response=200,
     *           description="Thêm từ thành công.",
     *           @OA\JsonContent(
     *               @OA\Property(property="word_name", type="string", example="Constant"),
     *               @OA\Property(property="specialization_id", type="integer", example=2),
     *               @OA\Property(property="synonymous", type="string", example="abc"),
     *               @OA\Property(property="antonyms", type="string", example="xyz"),
     *               @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-27T04:58:55.000000Z"),
     *               @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-27T04:58:55.000000Z"),
     *               @OA\Property(property="id", type="integer", example=1)
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
     * @param \App\Http\Requests\WordRequest\StoreWordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeWord(StoreWordRequest $request): JsonResponse
    {
        try {
            $data = $this->wordRepository->createWord($request->all());
            return $data ?
                $this->responseSuccess($data, 'Thêm từ thành công.')
                :
                $this->responseError(null, 'Đã có lỗi xảy ra!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
