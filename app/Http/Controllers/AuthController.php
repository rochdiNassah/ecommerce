<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\{LoginRequest, JoinRequest};
use App\Models\User;
use App\Services\{Authentication, RequestJoin};

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     * 
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuinate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $responsable = app(Authentication::class, ['request' => $request]);

        $credentials = $request->safe()->only('email', 'password');
        $remember =  $request->safe()->only('remember');

        Auth::attempt($credentials, $remember)
            ? $responsable->success()
            : $responsable->failed();
        
        return $responsable;
    }

    /**
     * Log the user out from the application.
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $responsable = app(Authentication::class, ['request' => $request]);
        
        $responsable->logout();

        return $responsable;
    }

    /**
     * Store a join request.
     * 
     * @param  \App\Http\Requests\JoinRequest
     * @return \Illuminate\Http\Response
     */
    public function join(JoinRequest $request)
    {
        $responsable = app(RequestJoin::class, ['request' => $request]);

        $responsable->store();

        return $responsable;
    }
}