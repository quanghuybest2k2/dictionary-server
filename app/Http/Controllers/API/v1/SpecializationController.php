<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Word;
use Illuminate\Http\Request;
use App\Models\Specialization;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\SpecializationRepositoryService\ISpecializationRepository;

class SpecializationController extends Controller
{
    private $specializationRepository;
    public function __construct(
        ISpecializationRepository $specializationRepository,
    ) {
        $this->specializationRepository = $specializationRepository;
    }
    public function getAll()
    {
        $specialization = $this->specializationRepository->getAll();
        return response()->json([
            'status' => Response::HTTP_OK,
            'specialization' => $specialization
        ]);
    }
    public function DisplayBySpecialization(Request $request)
    {
        $specializationId = $request->specialization_id;
        $specializations = $this->specializationRepository->getBySpecializationId($specializationId);

        // $specializations luôn trả về object nên không tìm thấy thì trả về null nên cần check $specializations->isEmpty()
        // if ($specializations->isEmpty()) {
        //     return response()->json([
        //         'status' => Response::HTTP_NOT_FOUND,
        //         'error' => 'Không có từ vựng thuộc về chuyên ngành!',
        //     ], Response::HTTP_NOT_FOUND);
        // }

        return response()->json([
            'status' => Response::HTTP_OK,
            'specializations' => $specializations
        ]);
    }
}
