<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;
use App\Models\Order;

class DeliveryDriverDashboardResponse implements Responsable
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
        $orders = Order::where('delivery_driver_id', $member->id)
            ->where($whereCallback)->where('delivery_driver_id', $member->id)
            ->orderBy('status', 'desc')
            ->get();
    }
}