<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', [ConversationController::class, 'index'])->name('home');
});
Route::get('/home', [ConversationController::class, 'index'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::resource('conversations', ConversationController::class);
