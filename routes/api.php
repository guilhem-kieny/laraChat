<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConversationApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    //Route::get('/user', [AuthController::class, 'currentUser']);
});
Route::get('/conversation/{id}', [ConversationApiController::class, 'show'])->name('api.conversation.show');
Route::post('/conversation/{id}/message', [ConversationApiController::class, 'store'])->name('api.conversation.store');
