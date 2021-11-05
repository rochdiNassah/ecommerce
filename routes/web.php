<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, ViewController, OrderController};
use App\Http\Controllers\{MemberController, ProductController};

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
    Route::get('/', [ViewController::class, 'home'])->name('home');

    // Login
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Forgot password
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

    // Reset password
    Route::get('/reset-password/{token}', [ViewController::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    // Request join
    Route::view('/join', 'auth.join')->name('join');
    Route::post('/join', [AuthController::class, 'join']);

    // Order
    Route::name('order.')->group(function () {
        Route::prefix('order')->group(function () {
            Route::get('/cancel/{token}', [OrderController::class, 'cancel'])->name('cancel');
            Route::get('/create/{product_id}', [ViewController::class, 'createOrder'])->name('create-view');
            Route::post('/create', [OrderController::class, 'create'])->name('create');
            Route::view('/request-my-orders', 'order.request-my-orders')->name('request-my-orders-view');
            Route::post('/request-my-orders', [OrderController::class, 'requestMyOrders'])->name('request-my-orders');
            Route::get('/my-orders/{email}/{token}', [ViewController::class, 'myOrders'])->name('my-orders');
            Route::get('/track/{token}', [ViewController::class, 'trackOrder'])->name('track-view');
        });
    });
});
Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [ViewController::class, 'dashboard'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/members', [ViewController::class, 'members'])->name('members');
        Route::get('/products', [ViewController::class, 'products'])->name('products');

        Route::name('member.')->group(function () {
            Route::prefix('member')->group(function () {
                Route::get('/pending', [ViewController::class, 'pendingMembers'])->name('pending');
                Route::get('/approve/{member_id}', [MemberController::class, 'approve'])->name('approve');
                Route::get('/delete/{member_id}', [MemberController::class, 'delete'])->name('delete');
                Route::get('/update-role/{member_id}', [ViewController::class, 'updateMemberRole'])->name('update-role-view');
                Route::post('/update-role', [MemberController::class, 'updateRole'])->name('update-role');
            });
        });
        Route::name('product.')->group(function () {
            Route::prefix('product')->group(function () {
                Route::view('/create', 'admin.product.create')->name('create-view');
                Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('delete');
                Route::post('/create', [ProductController::class, 'create'])->name('create');
            });
        });
    });
    Route::name('order.')->group(function () {
        Route::prefix('order')->group(function () {
            Route::middleware('role:dispatcher')->group(function () {
                Route::get('/reject/{order_id}', [OrderController::class, 'reject'])->name('reject');
                Route::get('/dispatch/{order_id}', [ViewController::class, 'dispatchOrder'])->name('dispatch-view');
                Route::post('/dispatch', [OrderController::class, 'dispatchOrder'])->name('dispatch');
            });
            Route::middleware('role:delivery_driver')->group(function () {
                Route::get('/{orderId}/update-status/{status}', [OrderController::class, 'updateStatus'])->name('update-status');
            });
        });
    });
});
