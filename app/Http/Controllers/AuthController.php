<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use RuntimeException;
use Illuminate\Support\Facades\{Auth, Hash, Storage};
use App\Http\Requests\LoginRequest;
use App\Http\Requests\JoinRequest;
use App\Models\User;

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
        $credentials = $request->safe()->only('email', 'password');
        $remember =  $request->safe()->only('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if ('active' !== Auth::user()->status) {
                $message = Auth::user()->status == 'pending'
                                                    ? 'Your account is not yet approved. We will notify you once we do.'
                                                    : 'Your account is locked. Please contact us for more information.';

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();
                $request->flashOnly('email');

                return back()->with([
                    'status' => 'warning',
                    'message' => $message
                ]);
            }

            return redirect()->intended('/dashboard')->with([
                'status' => 'success',
                'message' => 'Logged In successfully.'
            ]);
        }

        $request->flashOnly('email');

        return back()->with([
            'status' => 'error',
            'message' => 'The provided credentials do not match our records.'
        ]);
    }

    /**
     * Log the user out from the application.
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with([
            'status' => 'success',
            'message' => 'Logged Out successfully.'
        ]);
    }

    /**
     * Store a join request.
     * 
     * @param  \App\Http\Requests\JoinRequest
     * @return \Illuminate\Http\Response
     */
    public function join(JoinRequest $request)
    {
        $data = $request->safe()->only('fullname', 'email', 'phone_number', 'password', 'role');

        $data['password'] = Hash::make($data['password']);

        if ($request->file('avatar')) {
            if (! $data['avatar_path'] = Storage::putFile("images/avatars", $request->file('avatar'))) {
                $response = [
                    'status' => 'warning',
                    'message' => 'Something went wrong when we tried to store your avatar! Please try again.'
                ];
    
                $request->flashExcept('password', 'password_confirmation', 'avatar');

                return back()->with($response);
            }
        }

        try {
            $user = User::create($data);

            $response = [
                'status' => 'success',
                'message' => 'You will receive a confirmation once we review your request.'
            ];
        } catch (QueryException $e) {
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong! Please try again.'
            ];
        }

        return back()->with($response);
    }
}
