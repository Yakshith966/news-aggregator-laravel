<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsFeedController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('forgot-password', [ResetPasswordController::class, 'forgotPassword'])->name('password.request');
Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('articles', [ArticleController::class, 'index'])->name('article.index');
    Route::get('articles/{article}', [ArticleController::class, 'show'])->name('article.show');

    Route::post('preferences', [PreferenceController::class, 'store']);
    Route::get('preferences', [PreferenceController::class, 'index']);

    Route::get('newsfeed',  [NewsFeedController::class, 'index']);
});