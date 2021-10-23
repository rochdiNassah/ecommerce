<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class Authentication extends Service
{
    private $success = false;

    /**
     * Authentication attempt succeed.
     * 
     * @return void
     */
    public function success(): void
    {
        $this->response = [
            'status' => 'success',
            'message' => __('login.success')
        ];

        if ('pending' === Auth::user()->status) {
            $this->pending();
        } else {
            $this->request->session()->regenerate();
            
            $this->success = true;
        }        
    }

    /**
     * Authentication attempt failed.
     * 
     * @return void
     */
    public function failed(): void
    {
        $this->response = [
            'status' => 'error',
            'message' => __('login.failed')
        ];

        $this->flashInputs();
    }

    /**
     * The user who logged in is still pending.
     * 
     * @return void
     */
    private function pending(): void
    {
        $this->response = [
            'status' => 'warning',
            'message' => __('login.pending')
        ];

        $this->flashInputs();
        $this->logout();
    }

    /**
     * Log the user out from the application.
     * 
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();

        $this->request->session()->regenerate();
        $this->request->session()->regenerateToken();

        if (!$this->response) {
            $this->response = [
                'status' => 'success',
                'message' => __('logout.success')
            ];
            $this->redirectTo = 'home';
        }
    }

    /**
     * Flash inputs to the session.
     * 
     * @return void
     */
    private function flashInputs(): void
    {
        $this->request->flashExcept('password');
    }

    public function toResponse($request)
    {
        if ($this->success) {
            return redirect()->intended(route('dashboard'))->with($this->response);
        }
        if ($this->redirectTo) {
            return redirect(route($this->redirectTo))->with($this->response);
        }
        
        return back()->with($this->response);
    }
}