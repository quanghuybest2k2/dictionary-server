<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use App\Models\Means;
use App\Models\WordType;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
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
    )
    {
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
}
