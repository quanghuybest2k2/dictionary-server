<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Models\Specialization;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\SpecializationRepositoryService\ISpecializationRepository;

class SpecializationController extends Controller
{
    use ResponseTrait;

    private $specializationRepository;

    public function __construct(
        ISpecializationRepository $specializationRepository,
    )
    {
        $this->specializationRepository = $specializationRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/get-all-specialization",
     *      tags={"Specialization"},
     *      summary="Get all specializations",
     *      description="Retrieve a list of all specializations",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200)
     *          ),
     *      ),
     * )
     */
    public function getAll()
    {
        try {
            $specialization = $this->specializationRepository->getAll();
            return $this->responseSuccess($specialization, "Lấy thành công tất cả chuyên ngành.");

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/v1/display-by-specialization",
     *      tags={"Specialization"},
     *      summary="Get words by specialization ID",
     *      description="Retrieve words belonging to a specific specialization by ID",
     *      @OA\Parameter(
     *          name="specialization_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *          description="ID of the specialization"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200)
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Specializations not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=404),
     *              @OA\Property(property="error", type="string", example="Không có từ vựng thuộc về chuyên ngành!")
     *          )
     *      ),
     * )
     */
    public function DisplayBySpecialization(Request $request)
    {
        try {
            $specializationId = $request->specialization_id;
            $specializations = $this->specializationRepository->getBySpecializationId($specializationId);

            // $specializations luôn trả về object nên không tìm thấy thì trả về null nên cần check $specializations->isEmpty()
            // if ($specializations->isEmpty()) {
            //     return response()->json([
            //         'status' => Response::HTTP_NOT_FOUND,
            //         'error' => 'Không có từ vựng thuộc về chuyên ngành!',
            //     ], Response::HTTP_NOT_FOUND);
            // }

            return $this->responseSuccess($specializations, 'Lấy thành công từ vựng theo chuyên ngành!');

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
