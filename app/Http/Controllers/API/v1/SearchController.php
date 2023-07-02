<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Means;
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
            $findWord = $this->wordRepository->search($request->input('keyword'));
            if ($findWord) {

                $specializationName = $this->specializationRepository->find($findWord->specialization_id)->specialization_name;

                $means = $this->meansRepository->findByWordId($findWord->id);

                $wordTypeName = $this->wordTypeRepository->find($means->word_type_id)->type_name;

                $synonymous = $findWord->synonymous;

                $antonyms = $findWord->antonyms;

                return response()->json([
                    'status' => Response::HTTP_OK,
                    'word_name' => $findWord->word_name,
                    'type_name' => $wordTypeName,
                    'pronunciations' => $findWord->pronunciations,
                    'specialization_name' => $specializationName,
                    'means' => $means->means,
                    'description' => $means->description,
                    'example' => $means->example,
                    'synonymous' => $synonymous,
                    'antonyms' => $antonyms,
                ]);
            } else {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'error' => 'Không tìm thấy từ vựng!'
                ], Response::HTTP_NOT_FOUND);
            }
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
            $words = Word::with('specialization', 'means')
                ->where('word_name', 'like', '%' . $searched_word . '%')
                ->where('specialization_id', $specialization_id)
                ->get();

            if ($words->isEmpty()) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'error' => 'Không tìm thấy từ vựng!'
                ], Response::HTTP_NOT_FOUND);
            }

            foreach ($words as $word) {
                $wordName = $word->word_name;
                $pronunciations = $word->pronunciations;
                $specializationName = $word->specialization->specialization_name;
                $means = $word->means->pluck('means')->all();
                foreach ($word->means as $mean) {
                    $wordTypeName = $mean->WordType->type_name;
                }
                $description = $word->means->pluck('description')->all();
                $example = $word->means->pluck('example')->all();
                $synonymous = $word->synonymous;
                $antonyms = $word->antonyms;
            }

            return response()->json([
                'status' => Response::HTTP_OK,
                'word_name' => $wordName,
                'type_name' => $wordTypeName,
                'pronunciations' => $pronunciations,
                'specialization_name' => $specializationName,
                'means' => $means,
                'description' => $description,
                'example' => $example,
                'synonymous' => $synonymous,
                'antonyms' => $antonyms,
            ]);
        }
    }
}
