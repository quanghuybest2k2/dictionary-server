<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\WordRepositoryService\IWordRepository;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Dictionary App for IT",
 * )
 */
class FrontEndController extends Controller
{
    private $wordRepository;

    public function __construct(
        IWordRepository $wordRepository,
    ) {
        $this->wordRepository = $wordRepository;
    }
    /**
     * @OA\Get(
     *     path="/api/v1/get-suggest-all",
     *     summary="Lấy tất cả từ gợi ý khi tìm kiếm",
     *     tags={"Get Suggest"},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="suggest_all", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy từ gợi ý",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="error", type="string", example="Hiện tại chưa có gợi ý!")
     *         )
     *     )
     * )
     */
    // gợi ý từ
    public function suggest_all()
    {
        // chỉ lấy cột word_name của các record thôi
        $suggestAll = $this->wordRepository->getAll()->pluck('word_name');

        if ($suggestAll->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Hiện tại chưa có gợi ý!',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'suggest_all' => $suggestAll
        ]);
    }
    /**
     * @OA\Get(
     *     path="/api/v1/get-suggest",
     *     summary="Lấy danh sách các từ gợi ý theo chuyên ngành cụ thể",
     *     tags={"Get Suggest"},
     *     @OA\Parameter(
     *         name="specialization_id",
     *         in="query",
     *         required=true,
     *         description="Nhập id của chuyên ngành",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách các từ gợi ý theo chuyên ngành",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="suggest_name",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string",
     *                     example="Ví dụ: 1"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy từ gợi ý",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=404
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Lỗi rồi"
     *             )
     *         )
     *     )
     * )
     */
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
