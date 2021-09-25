<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::middleware('guest')->group(function () {
    Route::view('/', 'home');
    Route::view('/login', 'auth.login');
    Route::view('/join', 'auth.join');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/join', [AuthController::class, 'join']);
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', [UserController::class, 'dashboard']);
});