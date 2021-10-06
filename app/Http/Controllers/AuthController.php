<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use RuntimeException;
use Illuminate\Support\Facades\{Auth, Hash, Storage};
use App\Http\Requests\{LoginRequest, JoinRequest};
use App\Models\User;
use App\Notifications\JoinRequested;

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
                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                $request->flashExcept('password');

                return back()
                    ->with([
                        'status' => 'warning',
                        'message' => __('login.pending')
                    ]);
            }

            return redirect()
                ->intended(route('dashboard'))
                ->with([
                    'status' => 'success',
                    'message' => __('login.success')
                ]);
        }

        $request->flashExcept('password');

        return back()
            ->with([
                'status' => 'error',
                'message' => __('login.failed')
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

        return redirect(route('home'))
            ->with([
                'status' => 'success',
                'message' => __('logout.success')
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
                $request->flashExcept('password', 'password_confirmation', 'avatar');

                return back()
                    ->with([
                        'status' => 'error',
                        'message' => __('global.failed')
                    ]);
            }
        }

        try {
            $user = User::create($data);

            $user->notify((new JoinRequested()));

            return redirect(route('login'))
                ->with([
                    'status' => 'success',
                    'message' => __('join.success')
                ]);
        } catch (QueryException $e) {
            return back()
                ->with([
                    'status' => 'error',
                    'message' => __('global.failed')
                ]);
        }
    }
}
