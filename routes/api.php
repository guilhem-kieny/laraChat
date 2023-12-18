<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConversationApiController;
use App\Http\Controllers\Api\MessageApiController;
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

    Route::resource('conversations', ConversationApiController::class)->only(['show', 'store', 'destroy']);
    Route::post('conversations/{id}/name', [ConversationApiController::class, 'updateName'])->name('api.conversations.name');
    // Route::resource('conversations/messages', MessageApiController::class)->only(['store']);
    Route::post('/conversations/{id}/messages', [MessageApiController::class, 'store'])->name('api.conversations.messages.store');

});


