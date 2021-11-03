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
        $whereCallback = function ($query) {
            $query->where('status', 'dispatched')
                ->orWhere('status', 'shipped');
        };
        $orders = Order::where('delivery_driver_id', $request->user()->id)
            ->where($whereCallback)
            ->orderBy('id', 'desc');

        if ($filter) {
            $orders->where('status', $filter);
        } else {
            $orders->orderBy('id', 'desc');
        }
        
        return view('delivery_driver.dashboard', [
            'orders' => $orders->paginate(12),
            'filter' => $filter
        ]);
    }
}