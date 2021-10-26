<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{PlaceOrderRequest, RejectOrderRequest, DispatchOrderRequest};
use App\Models\{Order, User};
use App\Services\{PlaceOrder, RejectOrder, DispatchOrder};
use App\Interfaces\Responses\PlaceOrderResponse;
use App\Interfaces\Responses\RejectOrderResponse;
use App\Interfaces\Responses\DispatchOrderResponse;

class OrderController extends Controller
{
    /**
     * Place an order.
     * 
     * @param  \App\Http\Requests\PlaceOrderRequest  $request
     * @return \App\Interfaces\Responses\PlaceOrderResponse
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
     * @param  \App\Services\\RejectOrder  $service
     * @param  int  $id
     * @return \App\Interfaces\Responses\RejectOrderResponse
     */
    public function reject(RejectOrder $service, int $id): RejectOrderResponse
    {
        $order = Order::findOrFail($id);;

        $order->status !== 'pending'
            ? $service->isNotPending($order->status)
            : $service->reject($order);

        return app(RejectOrderResponse::class);
    }

    /**
     * Dispatch the given order.
     * 
     * @param  \App\Http\Requests\DispatchOrderRequest  $request
     * @return \App\Interfaces\Responses\DispatchOrderResponse
     */
    public function dispatchOrder(DispatchOrderRequest $request, DispatchOrder $service): DispatchOrderResponse
    {
        $order = Order::findOrFail($request->order_id);
        $delivery_driver = User::findOrFail($request->delivery_driver_id);
        
        $delivery_driver->role !== 'delivery_driver'
            ? $service->isNotDeliveryDriver()
            : ('pending' !== $order->status
                ? $service->isNotPending($order->status)
                : $service->dispatch($order, $delivery_driver));

        return app(DispatchOrderResponse::class);
    }
}
