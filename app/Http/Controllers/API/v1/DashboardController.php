<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    use ResponseTrait;

    public function index(): JsonResponse
    {
        $data = [
            'message' => "Hello",
            'message2' => "world!"
        ];
        return $this->responseSuccess($data);
    }
}
