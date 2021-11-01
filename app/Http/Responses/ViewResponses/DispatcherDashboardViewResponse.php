<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;
use App\Models\Order;

class DispatcherDashboardViewResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\View
     */
    public function toResponse($request): View
    {
        $orders = Order::where('status', '!=', 'rejected')
            ->where('status', '!=', 'canceled')
            ->orderBy('status', 'asc')
            ->get();

        return view('dispatcher.dashboard', ['orders' => $orders]);
    }
}