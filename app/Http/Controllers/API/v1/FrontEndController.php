<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\WordRepositoryService\IWordRepository;

class FrontEndController extends Controller
{
    private $wordRepository;

    public function __construct(
        IWordRepository $wordRepository,
    ) {
        $this->wordRepository = $wordRepository;
    }
    // gợi ý từ
    public function suggest()
    {
        // chỉ lấy cột word_name của các record thôi
        $suggestNames = $this->wordRepository->getAll()->pluck('word_name');
        return response()->json([
            'status' => Response::HTTP_OK,
            'suggest_name' => $suggestNames
        ]);
    }
}
