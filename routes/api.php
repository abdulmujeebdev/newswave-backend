<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ArticlesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function() {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::get('index', [ArticlesController::class, 'index']);
    Route::get('article/filters', [ArticlesController::class, 'getFilters']);

//    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('auth/user', [AuthController::class, 'getUser']);
        Route::put('updateProfile', [AuthController::class, 'updateProfile']);
    Route::get('get/preferences', [ArticlesController::class, 'getUserPreferences']);
    Route::post('saveUserPreferences', [ArticlesController::class, 'saveUserPreferences']);
//    });
});

