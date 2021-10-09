<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\{User, Product};

class ViewController extends Controller
{
    /**
     * Render the dashboard view depending on user's role.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        return view($request->user()->role.'.dashboard', [
            'usersCount' => User::all()->count(),
            'productsCount' => Product::all()->count()
        ]);
    }

    /**
     * Display all members for admin.
     * 
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        return View::make('admin.user.index', [
            'users' => User::orderBy('status')->get()
        ]);
    }

    /**
     * Display the edit role screen.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUserRole(int $id)
    {
        return View::make('admin.user.update-role', ['user' => User::findOrFail($id)]);
    }

    /**
     * Display all products for admin.
     * 
     * @return \Illuminate\Http\Response
     */
    public static function products()
    {
        return View::make('admin.product.index', ['products' => Product::all()]);
    }

    /**
     * Display all products for customer.
     * 
     * @return \Illuminate\Http\Response
     */
    public static function home()
    {
        return View::make('home', ['products' => Product::all()]);
    }

    /**
     * Display create order view.
     * 
     * @return \Illuminate\Http\Response
     */
    public function createOrder($productId)
    {
        return View::make('order.create', ['product' => Product::findOrFail($productId)]);
    }
}
