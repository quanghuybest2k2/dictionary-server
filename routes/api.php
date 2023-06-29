<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\UserController;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('v1')->group(function () {
    //dang ky
    Route::post('register', [UserController::class, 'register']);
    // dang nhap
    Route::post('login', [UserController::class, 'login']);
    // user
    // Route::controller(FrontendController::class)->group(function () {
    //     Route::get('viewHomePage', 'index');
    //     Route::get('getCategory', 'category');
    // });
    // admin
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
