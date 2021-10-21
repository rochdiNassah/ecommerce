<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as Response;
use App\Models\{User, Product, Order};

class ViewController extends Controller
{
    /**
     * Render the dashboard view depending on user's role.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request): Response
    {
        if ('admin' === $request->user()->role) {
            $view = 'admin.dashboard';
            $data = [
                'usersCount' => User::all()->count(),
                'productsCount' => Product::all()->count()
            ];
        }

        if ('dispatcher' === $request->user()->role) {
            $view = 'dispatcher.dashboard';

            $data = [
                'orders' => Order::all(),
            ];
        }

        return view($view, $data);
    }

    /**
     * Display all members for admin.
     * 
     * @return \Illuminate\View\View
     */
    public function users(): Response
    {
        return View::make('admin.user.index', [
            'users' => User::orderBy('status')->get()
        ]);
    }

    /**
     * Display the edit role screen.
     * 
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function updateUserRole(int $id): Response
    {
        return View::make('admin.user.update-role', ['user' => User::findOrFail($id)]);
    }

    /**
     * Display all products for admin.
     * 
     * @return \Illuminate\View\View
     */
    public static function products(): Response
    {
        return View::make('admin.product.index', ['products' => Product::all()]);
    }

    /**
     * Display all products for customer.
     * 
     * @return \Illuminate\View\View
     */
    public static function home(): Response
    {
        return View::make('home', ['products' => Product::all()]);
    }

    /**
     * Display create order view.
     * 
     * @return \Illuminate\View\View
     */
    public function createOrder($productId): Response
    {
        return View::make('order.create', ['product' => Product::findOrFail($productId)]);
    }
}
