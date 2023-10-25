<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use App\Models\Means;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\WordRepositoryService\IWordRepository;
use App\Repositories\MeansRepositoryService\IMeansRepository;
use App\Repositories\WordTypeRepositoryService\IWordTypeRepository;
use App\Repositories\SpecializationRepositoryService\ISpecializationRepository;

class SearchController extends Controller
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
    public function search(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'keyword' => 'required',
                ],
                [
                    'required' => 'Vui lòng nhập :attribute',
                ],
                [
                    'keyword' => 'Từ vựng',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'validator_errors' => $validator->messages(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $word = $this->wordRepository->searchByKeyword($request->keyword);
                if ($word->isEmpty()) {
                    return $this->responseError(null, 'Không tìm thấy từ này!', Response::HTTP_NOT_FOUND);
                }
                return $this->responseSuccess($word, "Lấy thành công từ vựng.");
            }
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
    public function searchBySpecialty(Request $request)
    {
        try {
            $searched_word = $request->searched_word;
            $specialization_id = $request->specialization_id;

            $validator = Validator::make(
                $request->all(),
                [
                    'searched_word' => 'required',
                    'specialization_id' => 'required|integer',
                ],
                [
                    'required' => 'Vui lòng nhập :attribute',
                    'specialization_id.integer' => 'Id của chuyên ngành phải là số!',
                ],
                [
                    'searched_word' => 'Từ vựng cần tìm',
                    'specialization_id' => 'Id của chuyên ngành',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'validator_errors' => $validator->messages(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $word_by_specialty = $this->specializationRepository->findBySpecialty($searched_word, $specialization_id);
                if ($word_by_specialty->isEmpty()) {
                    return $this->responseError(null, 'Không tìm thấy từ vựng!', Response::HTTP_NOT_FOUND);
                }
                return $this->responseSuccess($word_by_specialty, "Lấy thành công từ vựng theo chuyên ngành.");
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
