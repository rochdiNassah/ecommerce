<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{PlaceOrderRequest, RejectOrderRequest};
use App\Models\Order;
use App\Services\PlaceOrder;
use App\Interfaces\Responses\PlaceOrderResponse;

class OrderController extends Controller
{
    /**
     * Place an order.
     * 
     * @param  \App\Http\Requests\PlaceOrderRequest  $request
     * @return \App\Services\PlaceOrder
     */
    public function create(PlaceOrderRequest $request): PlaceOrderResponse
    {
        $service = app(
            PlaceOrder::class,
            ['request' => $request]
        );

        $service->prepareData();
        $service->store();

        $service->notifyCustomer()
            ? $service->succeed()
            : $service->failed();
            
        return app(PlaceOrderResponse::class);
    }

    /**
     * Reject the given order.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order->status = 'rejected';

        $order->save();

        return back()->with([
            'status' => 'success',
            'message' => __('order.rejected')
        ]);
    }
}
