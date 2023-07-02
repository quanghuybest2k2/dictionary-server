<?php

use App\Http\Controllers\API\v1\FrontEndController;
use App\Http\Controllers\API\v1\SearchController;
use App\Http\Controllers\API\v1\SpecializationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\UserController;
use App\Http\Controllers\API\v1\WordController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('v1')->group(function () {
    // ====================================== For Everyone ======================================
    //dang ky
    Route::post('register', [UserController::class, 'register']);
    // dang nhap
    Route::post('login', [UserController::class, 'login']);
    // client
    Route::controller(FrontEndController::class)->group(function () {
        Route::get('get-suggest', 'suggest');
    });
    // ====================================== For User ======================================
    // từ
    Route::controller(WordController::class)->group(function () {
        Route::get('random-word', 'getRandomWord');
    });
    Route::controller(SearchController::class)->group(function () {
        Route::get('search-word', 'search');
    });
    // chuyên ngành
    Route::controller(SpecializationController::class)->group(function () {
        Route::get('get-all-specialization', 'getAll');
        Route::get('display-by-specialization', 'DisplayBySpecialization');
    });

    // ====================================== For Admin ======================================
    Route::middleware('auth:sanctum', 'isAPIAdmin')->group(function () {

        Route::get('/checkingAuthenticated', function () {
            return response()->json(['message' => 'Bạn đã đăng nhập', 'status' => 200], 200);
        });
        // // Dashboard
        // Route::controller(DashboardController::class)->group(function () {
        //     Route::get('view-dashboard', 'index');
        // });
    });

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', [UserController::class, 'logout']);
    });
});
