<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use App\Models\Means;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\SearchRequest\SearchRequest;
use App\Http\Requests\SearchRequest\SearchHistoryRequest;
use App\Repositories\WordRepositoryService\IWordRepository;
use App\Http\Requests\SearchRequest\SearchBySpecialtyRequest;
use App\Repositories\MeansRepositoryService\IMeansRepository;
use App\Repositories\WordTypeRepositoryService\IWordTypeRepository;
use App\Repositories\HistoriesRepositoryService\IHistoriesRepository;
use App\Repositories\SpecializationRepositoryService\ISpecializationRepository;

class SearchController extends Controller
{
    use ResponseTrait;

    private $wordRepository;
    private $specializationRepository;
    private $meansRepository;
    private $wordTypeRepository;
    private $iHistoriesRepository;

    public function __construct(
        IWordRepository           $wordRepository,
        ISpecializationRepository $specializationRepository,
        IMeansRepository          $meansRepository,
        IWordTypeRepository       $wordTypeRepository,
        IHistoriesRepository      $iHistoriesRepository
    )
    {
        $this->wordRepository = $wordRepository;
        $this->specializationRepository = $specializationRepository;
        $this->meansRepository = $meansRepository;
        $this->wordTypeRepository = $wordTypeRepository;
        $this->iHistoriesRepository = $iHistoriesRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/search-word",
     *      tags={"Search"},
     *      summary="Search for a word",
     *      description="Search for a word by keyword",
     *      @OA\Parameter(
     *          name="keyword",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200)
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Word not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="error", type="string", example="Không tìm thấy từ này!")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="validator_errors", type="object", example={"keyword": {"Vui lòng nhập Từ vựng"}})
     *          )
     *      ),
     * )
     */
    public function search(SearchRequest $request): JsonResponse
    {
        try {
            $word = $this->wordRepository->searchByKeyword($request->keyword);
            if ($word->isEmpty()) {
                return $this->responseError(null, 'Không tìm thấy từ này!', Response::HTTP_NOT_FOUND);
            }
            return $this->responseSuccess($word, "Lấy thành công từ vựng.");
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/v1/search-by-specialty",
     *      tags={"Search"},
     *      summary="Search words by specialty",
     *      description="Search for words by a specific specialty and searched word",
     *      @OA\Parameter(
     *          name="searched_word",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          description="The word to search for"
     *      ),
     *      @OA\Parameter(
     *          name="specialization_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *          description="ID of the specialization to filter by"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200)
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Word not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="error", type="string", example="Không tìm thấy danh sách từ vựng!")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="validator_errors", type="object", example={"searched_word": {"Vui lòng nhập Từ vựng"}, "specialization_id": {"Id của chuyên ngành phải là số!"}})
     *          )
     *      ),
     * )
     */
    public function searchBySpecialty(SearchBySpecialtyRequest $request): JsonResponse
    {
        try {
            $searched_word = $request->searched_word;
            $specialization_id = $request->specialization_id;

            $word_by_specialty = $this->specializationRepository->findBySpecialty($searched_word, $specialization_id);
            if ($word_by_specialty->isEmpty()) {
                return $this->responseError(null, 'Không tìm thấy từ vựng!', Response::HTTP_NOT_FOUND);
            }
            return $this->responseSuccess($word_by_specialty, "Lấy thành công từ vựng theo chuyên ngành.");
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/v1/search-word-lookup-history",
     *      tags={"Search"},
     *      summary="Search Word Lookup History",
     *      description="Search for words by a user id and english",
     *      @OA\Parameter(
     *          name="english",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          description="English"
     *      ),
     *      @OA\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *          description="User id"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200)
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Word not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="error", type="string", example="Không tìm thấy danh sách từ vựng!")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="validator_errors", type="object", example={"searched_word": {"Vui lòng nhập Từ vựng"}, "specialization_id": {"Id của chuyên ngành phải là số!"}})
     *          )
     *      ),
     * )
     */
    public function searchWordLookupHistory(SearchHistoryRequest $request): JsonResponse
    {
        try {
            $result = $this->iHistoriesRepository->searchWordLookupHistory($request->english, $request->user_id);

            if (!$result) {
                return $this->responseError(null, "Lấy không thành công!");
            }
            return $this->responseSuccess($result, 'Lấy thành công', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/v1/search-translate-history",
     *      tags={"Search"},
     *      summary="Search Translate History",
     *      description="Search for translate by a user id and english",
     *      @OA\Parameter(
     *          name="english",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          description="English"
     *      ),
     *      @OA\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *          description="User id"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200)
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Word not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="error", type="string", example="Không tìm thấy bản dịch!")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="validator_errors", type="object", example={"searched_word": {"Vui lòng nhập bản dịch"}, "specialization_id": {"Id của chuyên ngành phải là số!"}})
     *          )
     *      ),
     * )
     */
    public function searchTranslateHistory(SearchHistoryRequest $request): JsonResponse
    {
        try {
            $result = $this->iHistoriesRepository->searchTranslateHistory($request->english, $request->user_id);

            if (!$result) {
                return $this->responseError(null, "Lấy không thành công!");
            }
            return $this->responseSuccess($result, 'Lấy thành công', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
