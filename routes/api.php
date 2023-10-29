<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\LoveController;
use App\Http\Controllers\API\v1\UserController;
use App\Http\Controllers\API\v1\WordController;
use App\Http\Controllers\API\v1\SearchController;
use App\Http\Controllers\API\v1\HistoryController;
use App\Http\Controllers\API\v1\FrontEndController;
use App\Http\Controllers\API\v1\DashboardController;
use App\Http\Controllers\API\v1\HotVocabularyController;
use App\Http\Controllers\API\v1\SpecializationController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    // ====================================== For Everyone ======================================
    //  User
    Route::controller(UserController::class)->group(function () {
        //dang ky
        Route::post('register', 'register')->name('register');
        // dang nhap
        Route::post('login', 'login')->name('login'); // có cái name này để biết login có định nghĩa App\Http\Middleware\Authenticate:15 redirectTo
        // lấy thông tin người dùng bằng id
        Route::get('get-user/{id}', 'getUser')->name('getUser');
        // cập nhật thông tin
        Route::put('update-user/{id}', 'update')->name('updateUser');
        // xoa user
        Route::delete('delete-user/{id}', 'destroyUser')->name('deleteUser');
    });
    // client
    Route::controller(FrontEndController::class)->group(function () {
        Route::get('get-suggest-all', 'suggest_all')->name('getSuggestAll');
        Route::get('get-suggest', 'suggest')->name('getSuggest');
    });
    // ====================================== For User ======================================
    // từ
    Route::controller(WordController::class)->group(function () {
        Route::get('random-word', 'getRandomWord')->name('getRandomWord');
    });
    Route::controller(SearchController::class)->group(function () {
        Route::get('search-word', 'search')->name('searchWord');
        Route::get('search-by-specialty', 'searchBySpecialty')->name('searchBySpecialty');
    });

    // chuyên ngành
    Route::controller(SpecializationController::class)->group(function () {
        Route::get('get-all-specialization', 'getAll')->name('getAllSpecialization');
        Route::get('display-by-specialization', 'DisplayBySpecialization')->name('displayBySpecialization');
    });

    // lịch sử
    Route::controller(HistoryController::class)->group(function () {
        Route::get('check-if-exist', 'checkIfExist')->name('checkIfExist');
        // lấy lịch sử tra từ của user cụ thể
        Route::get('get-word-lookup-history/{user_id}', 'getWordLookupHistory')->name('getWordLookupHistory');
        // lưu lịch sử tra từ
        Route::post('save-word-lookup-history', 'storeWordLookupHistory')->name('saveWordLookupHistory');
        // hiển thị lịch sử tra từ của user cụ thể
        Route::get('get-translate-history/{user_id}', 'loadTranslateHistoryByUser')->name('getTranslateHistory');
        // lưu lịch sử dịch
        Route::post('save-translate-history', 'storeTranslateHistory')->name('saveTranslateHistory');
        // xóa lịch sử
        Route::delete('delete-translate-history/{user_id}', 'destroy')->name('deleteTranslateHistory');
        Route::delete('delete-translate-by-id/{user_id}/{id}', 'destroyById')->name('deleteTranslateById');
    });
    // yêu thích
    Route::controller(LoveController::class)->group(function () {
        // lưu từ vựng yêu thích
        Route::post('save-love_vocabulary', 'saveLoveVocabulary')->name('saveLoveVocabulary');
        // xóa từ yêu thích
        Route::delete('delete-love_vocabulary/{english}/{user_id}', 'destroyLoveVocabulary')->name('deleteLoveVocabulary');
        // lấy tổng mục yêu thích của user
        Route::get('total-love-item/{user_id}', 'TotalLoveItemOfUser')->name('totalLoveItem');
        // Thêm văn bản
        Route::post('save-love_text', 'saveLoveText')->name('saveLoveText');
        // Xóa văn bản
        Route::delete('delete-love_text', 'destroyLoveText')->name('deleteLoveText');
    });
    // từ vựng hot
    Route::controller(HotVocabularyController::class)->group(function () {
        // lưu từ vựng yêu thích
        Route::get('get-hot-vocabulary', 'getHotVocabulary')->name('getHotVocabulary');
    });
    // ====================================== For Admin ======================================
    Route::middleware('auth:sanctum', 'isAPIAdmin')->group(function () {
        // Nếu xác thực admin thì đã đăng nhập
        Route::get('/checkingAuthenticated', function () {
            return response()->json([
                'status'  => true,
                'message' => 'Bạn đã đăng nhập',
                'errors'  => null,
                'data'    => null,

            ], 200)->name('checkAuthenticated');
        });

        // Dashboard
        Route::controller(DashboardController::class)->group(function () {
            Route::get('view-dashboard', 'index')->name('viewDashboard');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', [UserController::class, 'logout'])->name('logout');
    });
});
