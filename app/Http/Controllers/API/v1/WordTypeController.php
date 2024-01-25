<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Repositories\WordTypeRepositoryService\IWordTypeRepository;

class WordTypeController extends Controller
{
    use ResponseTrait;

    private $IWordTypeRepository;

    public function __construct(
        IWordTypeRepository           $IWordTypeRepository
    ) {
        $this->IWordTypeRepository = $IWordTypeRepository;
    }
    /**
     * @OA\Get(
     *      path="/api/v1/get-all-word-type",
     *      tags={"Word Type"},
     *      summary="Get all Word Type",
     *      description="Retrieve a list of all word type",
     *      @OA\Response(
     *          response=200,
     *          description="Lấy thành công tất cả từ loại.",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200)
     *          ),
     *      ),
     * )
     */
    public function getAllWordType()
    {
        try {
            $specialization = $this->IWordTypeRepository->getAll();
            return $specialization ?
                $this->responseSuccess($specialization, "Lấy thành công tất cả từ loại.")
                :
                $this->responseError(null, 'Lấy thất bại!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
