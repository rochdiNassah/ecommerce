<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($role !== $request->user()->role) {
            return redirect(route('dashboard'))
                ->with([
                    'status' => 'warning',
                    'message' => __('global.unauthorized'),
                    'reason' => 'Unauthorized'
                ]);
        }
        
        return $next($request);
    }
}
