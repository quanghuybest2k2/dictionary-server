<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use App\Models\Means;
use App\Models\WordType;
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
    public function getRandomWord()
    {
        // $randomWord = Word::inRandomOrder()->first();
        // $specializationName = $randomWord->specialization->specialization_name;
        // $means = $randomWord->means->first();
        // $wordTypeName = $means->wordType->type_name;

        $randomWord = $this->wordRepository->getRandomWord();
        if ($randomWord) {

            $specializationName = $this->specializationRepository->find($randomWord->specialization_id)->specialization_name;

            $means = $this->meansRepository->findByWordId($randomWord->id);

            $wordTypeName = $this->wordTypeRepository->find($means->word_type_id)->type_name;

            $synonymous = $randomWord->synonymous;

            $antonyms = $randomWord->antonyms;

            return response()->json([
                'status' => Response::HTTP_OK,
                'word_name' => $randomWord->word_name,
                'type_name' => $wordTypeName,
                'pronunciations' => $randomWord->pronunciations,
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
                'keyword' => 'Từ khóa',
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
}
