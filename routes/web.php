<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\{MemberController, ProductController};
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
        Route::get('/users', [MemberController::class, 'users'])->name('users');
        Route::get('/products', [ProductController::class, 'products'])->name('products');

        Route::name('user.')->group(function () {
            Route::prefix('users')->group(function () {
                Route::get('/approve/{id}', [MemberController::class, 'approve'])->name('approve');
                Route::get('/delete/{id}', [MemberController::class, 'delete'])->name('delete');
                Route::get('/update-role/{id}', [MemberController::class, 'updateRoleScreen'])->name('update-role-screen');
                Route::post('/update-role', [MemberController::class, 'updateRole'])->name('update-role');
            });
        });

        Route::name('product.')->group(function () {
            Route::prefix('products')->group(function () {
                Route::view('/create', 'admin.product.create')->name('create');
            });
        });
    });
});