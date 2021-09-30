<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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
    Route::view('/', 'home')->name('home');
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/join', 'auth.join')->name('join');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/join', [AuthController::class, 'join']);
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/account', function () { return 'MY ACCOUNT'; })->name('account');

    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'users'])->name('users');
        Route::get('/products', function () { return 'PRODUCTS'; })->name('products');

        Route::name('user.')->group(function () {
            Route::prefix('users')->group(function () {
                Route::get('/approve/{id}', [UserController::class, 'approveUser'])->name('approve');
                Route::get('/update-role/{id}', function ($id) { return 'UPDATE ROLE '.$id; })->name('update-role');
                Route::get('/delete/{id}', function ($id) { return 'DELETE '.$id; })->name('delete');
            });
        });
    });
});