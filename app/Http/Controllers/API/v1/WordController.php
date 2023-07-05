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
            return response()->json([
                'status' => Response::HTTP_OK,
                'randomWord' => $randomWord,
            ]);
        } else {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Không tìm thấy từ vựng!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
