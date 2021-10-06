<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ('admin' !== $request->user()->role) {
            return redirect(route('dashboard'))
                ->with([
                    'status' => 'warning',
                    'message' => __('global.unautorized'),
                    'reason' => 'Unauthorized'
                ]);
        }
        
        return $next($request);
    }
}
