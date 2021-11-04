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
     * @return \Illuminate\View\View
     */
    public function toResponse($request): View
    {
        $filter = request('filter') ?? null;
        $orders = Order::where('status', '!=', 'rejected')
            ->where('status', '!=', 'canceled')
            ->where('status', '!=', 'delivered')
            ->orderBy('status', 'asc');

            if ($filter) {
                $orders->where('status', $filter);
            }
            
            $orders->orderBy('id', 'desc');
            
            return view('dispatcher.dashboard', [
                'orders' => $orders->paginate(12),
                'filter' => $filter
            ]);
    }
}