<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use App\Models\Means;
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
    private $wordRepository;
    private $specializationRepository;
    private $meansRepository;
    private $wordTypeRepository;

    public function __construct(
        IWordRepository $wordRepository,
        ISpecializationRepository $specializationRepository,
        IMeansRepository $meansRepository,
        IWordTypeRepository $wordTypeRepository
    ) {
        $this->wordRepository = $wordRepository;
        $this->specializationRepository = $specializationRepository;
        $this->meansRepository = $meansRepository;
        $this->wordTypeRepository = $wordTypeRepository;
    }
    public function search(Request $request)
    {
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
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'error' => 'Không tìm thấy từ này!'
                ], Response::HTTP_NOT_FOUND);
            }
            return response()->json([
                'status' => Response::HTTP_OK,
                'word' => $word
            ]);
        }
    }
    public function searchBySpecialty(Request $request)
    {
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
            // $words = Word::with('specialization', 'means')
            //     ->where('word_name', 'like', '%' . $searched_word . '%')
            //     ->where('specialization_id', $specialization_id)
            //     ->get();

            // foreach ($words as $word) {
            //     $wordName = $word->word_name;
            //     $pronunciations = $word->pronunciations;
            //     $specializationName = $word->specialization->specialization_name;
            //     $means = $word->means->pluck('means')->all();
            //     $wordTypeName = [];
            //     foreach ($word->means as $mean) {
            //         $wordTypeName[] = $mean->WordType->type_name;
            //     }
            //     $description = $word->means->pluck('description')->all();
            //     $example = $word->means->pluck('example')->all();
            //     $synonymous = $word->synonymous;
            //     $antonyms = $word->antonyms;
            // }
            $word_by_specialty = $this->specializationRepository->findBySpecialty($searched_word, $specialization_id);
            if ($word_by_specialty->isEmpty()) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'error' => 'Không tìm thấy danh sách từ vựng!'
                ], Response::HTTP_NOT_FOUND);
            }
            return response()->json([
                'status' => Response::HTTP_OK,
                'word_by_specialty' => $word_by_specialty
            ]);
        }
    }
}
