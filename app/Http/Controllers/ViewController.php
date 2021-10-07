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
     * Display all members.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        return View::make('admin.users', [
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
        return View::make('admin.user.update-role', [
            'user' => User::findOrFail($id)
        ]);
    }

    /**
     * Display all products.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function products()
    {
        return View::make('admin.products', [
            'products' => Product::all()
        ]);
    }
}
