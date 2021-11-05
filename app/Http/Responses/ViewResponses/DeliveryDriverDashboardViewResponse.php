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
        $filter = request('filter') ?? null;
        $orders = Order::where('delivery_driver_id', $request->user()->id)
            ->where('status', 'REGEXP', 'dispatched|shipped');

        !$filter ?: $orders->where('status', $filter);
                
        $orders = $orders->orderBy('id', 'desc')->paginate();

        return view('delivery_driver.dashboard', compact('orders', 'filter'));
    }
}