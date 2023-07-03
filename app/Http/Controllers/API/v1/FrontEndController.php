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
    public function suggest(Request $request)
    {
        // chỉ lấy cột word_name của các record thôi
        $suggestNames = Word::where('specialization_id', $request->specialization_id)->pluck('word_name');
        if ($suggestNames->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Hiện tại chưa có gợi ý!',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'suggest_name' => $suggestNames
        ]);
    }
}
