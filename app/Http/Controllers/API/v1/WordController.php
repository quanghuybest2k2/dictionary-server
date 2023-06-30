<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use App\Models\Means;
use App\Models\WordType;
use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Http\Controllers\Controller;
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

        $specializationName = $this->specializationRepository->find($randomWord->specialization_id)->specialization_name;

        $means = $this->meansRepository->findByWordId($randomWord->id);

        $wordTypeName = $this->wordTypeRepository->find($means->word_type_id)->type_name;

        return response()->json([
            'word_name' => $randomWord->word_name,
            'type_name' => $wordTypeName,
            'pronunciations' => $randomWord->pronunciations,
            'specialization_name' => $specializationName,
            'means' => $means->means,
            'description' => $means->description,
            'example' => $means->example,
        ]);
    }
}
