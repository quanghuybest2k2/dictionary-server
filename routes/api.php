<?php

use App\Http\Controllers\API\v1\FrontEndController;
use App\Http\Controllers\API\v1\HistoryController;
use App\Http\Controllers\API\v1\LoveController;
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
    //  User
    Route::controller(UserController::class)->group(function () {
        //dang ky
        Route::post('register', 'register');
        // dang nhap
        Route::post('login', 'login');
        // lấy thông tin người dùng bằng id
        Route::get('get-user/{id}', 'getUser');
        // xoa user
        Route::delete('delete-user/{id}', 'destroyUser');
    });
    // client
    Route::controller(FrontEndController::class)->group(function () {
        Route::get('get-suggest-all', 'suggest_all');
        Route::get('get-suggest', 'suggest');
    });
    // ====================================== For User ======================================
    // từ
    Route::controller(WordController::class)->group(function () {
        Route::get('random-word', 'getRandomWord');
    });
    Route::controller(SearchController::class)->group(function () {
        Route::get('search-word', 'search');
        Route::get('search-by-specialty', 'searchBySpecialty');
    });
    // chuyên ngành
    Route::controller(SpecializationController::class)->group(function () {
        Route::get('get-all-specialization', 'getAll');
        Route::get('display-by-specialization', 'DisplayBySpecialization');
    });
    // lịch sử
    Route::controller(HistoryController::class)->group(function () {
        Route::get('check-if-exist', 'checkIfExist');
        Route::get('load-translate-history-by-user', 'loadTranslateHistoryByUser');
        // lưu lịch sử tra từ
        Route::post('save-word-lookup-history', 'storeWordLookupHistory');
        // lưu lịch sử dịch
        Route::post('save-translate-history', 'storeTranslateHistory');
        // xóa lịch sử
        Route::delete('delete-translate-history/{user_id}', 'destroy');
        Route::delete('delete-translate-by-id/{user_id}/{id}', 'destroyById');
    });
    // yêu thích
    Route::controller(LoveController::class)->group(function () {
        // lưu từ vựng yêu thích
        Route::post('save-love_vocabulary', 'saveLoveVocabulary');
        // xóa từ yêu thích
        Route::delete('delete-love_vocabulary/{english}/{user_id}', 'destroyLoveVocabulary');
        // Thêm văn bản
        Route::post('save-love_text', 'saveLoveText');
        // Xóa văn bản
        Route::delete('delete-love_text', 'destroyLoveText');
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
