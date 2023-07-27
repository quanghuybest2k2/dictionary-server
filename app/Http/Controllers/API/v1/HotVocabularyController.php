<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\HotVocabularyRepositoryService\IHotVocabularyRepository;

class HotVocabularyController extends Controller
{
    private $iHotVocabularyRepository;
    public function __construct(IHotVocabularyRepository $iHotVocabularyRepository)
    {
        $this->iHotVocabularyRepository = $iHotVocabularyRepository;
    }
    public function getHotVocabulary()
    {
        try {
            $hotVocabulary = $this->iHotVocabularyRepository->getHotVocabulary();
            return  $hotVocabulary ?
                response()->json([
                    'status' => Response::HTTP_OK,
                    'hotVocabulary' => $hotVocabulary
                ], Response::HTTP_OK)
                :
                response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Hiện tại chưa có từ vựng! Rất xin lỗi về sự cố này.'
                ], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
