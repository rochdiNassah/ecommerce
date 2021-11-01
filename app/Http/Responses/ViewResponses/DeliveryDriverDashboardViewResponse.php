<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;
use App\Models\Order;

class DeliveryDriverDashboardViewResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\View
     */
    public function toResponse($request): View
    {
        $whereCallback = function ($query) {
            $query->where('status', 'dispatched')
                ->orWhere('status', 'shipped');
        };
        $orders = Order::where('delivery_driver_id', $request->user()->id)
            ->where($whereCallback)
            ->orderBy('status', 'desc')
            ->get();
        
        return view('delivery_driver.dashboard', ['orders' => $orders]);
    }
}