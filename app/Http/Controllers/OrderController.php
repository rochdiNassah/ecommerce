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
        $customer = json_encode(
            $request->safe()->only([
                'fullname', 'email', 'phone_number', 'address'
            ])
        );
        $product_id = $request->validated()['product_id'];
        $validated = ['product_id' => $product_id, 'customer' => $customer];

        if ($order = PlaceOrder::store($validated)) {
            PlaceOrder::notifyCustomer($order);
            PlaceOrder::succeed();
        } else {
            PlaceOrder::failed();
        }
            
        return app(PlaceOrderResponse::class);
    }

    /**
     * Reject the given order.
     * 
     * @param  int  $id
     * @return \App\Interfaces\Responses\RejectOrderResponse
     */
    public function reject(int $id): RejectOrderResponse
    {
        $order = Order::findOrFail($id);;

        if ('pending' !== $order->status) {
            RejectOrder::isNotPending($order->status);
        } else {
            RejectOrder::reject($order);
            RejectOrder::succeed();
        }

        return app(RejectOrderResponse::class);
    }

    /**
     * Dispatch the given order.
     * 
     * @param  \App\Http\Requests\DispatchOrderRequest  $request
     * @return \App\Interfaces\Responses\DispatchOrderResponse
     */
    public function dispatchOrder(DispatchOrderRequest $request): DispatchOrderResponse
    {
        $order = Order::findOrFail($request->order_id);
        $delivery_driver = User::findOrFail($request->delivery_driver_id);
        
        if ('delivery_driver' !== $delivery_driver->role) {
            DispatchOrder::isNotDeliveryDriver();
        } elseif('pending' !== $order->status) {
            DispatchOrder::isNotPending($order->status);
        } else {
            DispatchOrder::dispatch($order, $delivery_driver);
            DispatchOrder::succeed($delivery_driver);
        }

        return app(DispatchOrderResponse::class);
    }
}
