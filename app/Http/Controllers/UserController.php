<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Product};

class UserController extends Controller
{
    /**
     * Render dashboard view depending on user's role.
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
}