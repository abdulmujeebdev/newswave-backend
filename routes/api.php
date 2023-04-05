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

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('get_user', [AuthController::class, 'get_user']);
        Route::put('updateProfile', [AuthController::class, 'updateProfile']);
        Route::get('index', [ArticlesController::class, 'index']);
        Route::get('getSources', [ArticlesController::class, 'getSources']);
        Route::get('getAuthors', [ArticlesController::class, 'getAuthors']);
        Route::get('getCategories', [ArticlesController::class, 'getCategories']);
        Route::post('saveUserPreferences', [ArticlesController::class, 'saveUserPreferences']);
    });
});

