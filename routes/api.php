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
        Route::post('store-word', 'storeWord')->name('storeWord');
    });
    // tìm kiếm
    Route::controller(SearchController::class)->group(function () {
        Route::get('search-word', 'search')->name('searchWord');
        Route::get('search-by-specialty', 'searchBySpecialty')->name('searchBySpecialty');
        // tìm kiếm từ vựng trong lịch sử
        Route::get('search-word-lookup-history', 'searchWordLookupHistory')->name('searchWordLookupHistory');
        // tìm kiếm bản dịch trong lịch sử
        Route::get('search-translate-history', 'searchTranslateHistory')->name('searchTranslateHistory');
        // tìm kiếm từ vựng yêu thích
        Route::get('search-love-vocabulary-by-word', 'searchLoveVocabularyByWord')->name('searchLoveVocabularyByWord');
        // tìm kiếm văn bản yêu thích
        Route::get('search-love-text-by-word', 'searchLoveTextByWord')->name('searchLoveTextByWord');
        // tìm kiếm tổng số mục yêu thích bằng từ vựng và bản dịch
        Route::get('find-love-by-word-and-english', 'searchLoveByWordAndEnglish')->name('searchLoveByWordAndEnglish');
    });

    // chuyên ngành
    Route::controller(SpecializationController::class)->group(function () {
        Route::get('get-all-specialization', 'getAll')->name('getAllSpecialization');
        Route::get('display-by-specialization', 'DisplayBySpecialization')->name('displayBySpecialization');
    });

    // lịch sử
    Route::controller(HistoryController::class)->group(function () {
        Route::get('check-if-exist', 'checkIfExist')->name('checkIfExist');
        //  ------------------ từ ------------------------------------
        // lấy lịch sử tra từ của user cụ thể
        Route::get('get-word-lookup-history/{user_id}', 'getWordLookupHistory')->name('getWordLookupHistory');
        // lưu lịch sử tra từ
        Route::post('save-word-lookup-history', 'storeWordLookupHistory')->name('saveWordLookupHistory');
        // hiển thị theo thời gian của từ vựng
        Route::get('display-by-time-word-lookup-history', 'displayByTimeWordLookupHistory')->name('displayByTimeWordLookupHistory');
        //  xóa từng từ vựng trong lịch sử
        Route::delete('delete-by-id-word-lookup-history/{user_id}/{id}', 'deleteByIdWordLookupHistory')->name('deleteByIdWordLookupHistory');
        // xóa tất cả từ vựng trong lịch sử
        Route::delete('delete-word-lookup-history/{user_id}', 'deleteAllWordLookupHistory')->name('deleteAllWordLookupHistory');

        //  ------------------ dịch ------------------------------------
        // lưu lịch sử dịch
        Route::post('save-translate-history', 'storeTranslateHistory')->name('saveTranslateHistory');
        // hiển thị lịch sử tra từ của user cụ thể
        Route::get('get-translate-history/{user_id}', 'loadTranslateHistoryByUser')->name('getTranslateHistory');
        // hiển thị theo thời gian của bản dịch
        Route::get('display-by-time-translate-history', 'displayByTimeTranslateHistory')->name('displayByTimeTranslateHistory');
        // xóa từng bản dịch trong lịch sử
        Route::delete('delete-translate-by-id/{user_id}/{id}', 'deleteByIdTranslateHistory')->name('deleteByIdTranslateHistory');
        // xóa lịch sử dịch theo user id
        Route::delete('delete-translate-history/{user_id}', 'deleteAllTranslateHistory')->name('deleteAllTranslateHistory');
        // delete all
        Route::delete('delete-all-history/{user_id}', 'deleteAllHistory')->name('deleteAllHistory');
    });
    // yêu thích
    Route::controller(LoveController::class)->group(function () {
        // lấy tổng mục yêu thích của user
        Route::get('total-love-item/{user_id}', 'TotalLoveItemOfUser')->name('totalLoveItem');
        // Xóa hết record trong 2 bảng
        Route::delete('delete-all-favorite/{user_id}', 'destroyAllFavorite')->name('destroyAllFavorite');
        //  ------------------ từ ------------------------------------
        // hiển thị từ vựng yêu thích
        Route::get('show-love-vocabulary/{user_id}', 'showLoveVocabulary')->name('showLoveVocabulary');
        // lưu từ vựng yêu thích
        Route::post('save-love-vocabulary', 'saveLoveVocabulary')->name('saveLoveVocabulary');
        // xóa từ yêu thích
        Route::delete('delete-love-vocabulary/{english}/{user_id}', 'destroyLoveVocabulary')->name('destroyLoveVocabulary');
        // sắp xếp từ vựng yêu thích
        Route::get('sort-by-favorite-word-lookup/{user_id}', 'sortByFavoriteWordLookup')->name('sortByFavoriteWordLookup');
        // cập nhật ghi chú của từ vựng        
        Route::put('update-favorite-vocabulary/{id}/{user_id}', 'updateFavoriteVocabulary')->name('updateFavoriteVocabulary');
        //  ------------------ dịch ------------------------------------
        // hiển thị bản dịch yêu thích
        Route::get('show-love-text/{user_id}', 'showLoveText')->name('showLoveText');
        // Thêm văn bản
        Route::post('save-love-text', 'saveLoveText')->name('saveLoveText');
        // Xóa văn bản
        Route::delete('delete-love-text', 'destroyLoveText')->name('destroyLoveText');
        // sắp xếp bản dịch yêu thích
        Route::get('sort-by-favorite-text/{user_id}', 'sortByFavoriteText')->name('sortByFavoriteText');
        // cập nhật ghi chú của bản dịch        
        Route::put('update-favorite-text/{id}/{user_id}', 'updateFavoriteText')->name('updateFavoriteText');
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
                'status' => true,
                'message' => 'Bạn đã đăng nhập',
                'errors' => null,
                'data' => null,

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
})->middleware('cacheResponse:600');
