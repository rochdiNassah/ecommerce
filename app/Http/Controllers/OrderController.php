<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Gate, Notification};
use App\Models\{Order, Member};
use App\Http\Requests\{PlaceOrderRequest, RejectOrderRequest, DispatchOrderRequest};
use App\Services\{PlaceOrder, RejectOrder, DispatchOrder, UpdateOrderStatus, RequestMyOrders};
use App\Interfaces\Responses\PlaceOrderResponse;
use App\Interfaces\Responses\RejectOrderResponse;
use App\Interfaces\Responses\DispatchOrderResponse;
use App\Interfaces\Responses\UpdateOrderStatusResponse;
use App\Interfaces\Responses\RequestMyOrdersResponse;

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
            RejectOrder::reject($order, Auth::id());
            UpdateOrderStatus::publish($order);
            RejectOrder::succeed();;
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
        $delivery_driver = Member::findOrFail($request->delivery_driver_id);
        
        if ('delivery_driver' !== $delivery_driver->role) {
            DispatchOrder::isNotDeliveryDriver();
        } elseif('pending' !== $order->status) {
            DispatchOrder::isNotPending($order->status);
        } else {
            DispatchOrder::dispatch($order, $delivery_driver);
            DispatchOrder::succeed($delivery_driver);
            DispatchOrder::publish($order);
        }

        return app(DispatchOrderResponse::class);
    }

    /**
     * Update order's status.
     * 
     * @param  int  $id
     * @param  string  $status
     * @return \App\Interfaces\Responses\UpdateOrderStatusResponse
     */
    public function updateStatus(int $id, string $status): UpdateOrderStatusResponse
    {
        $order = Order::findOrFail($id);

        UpdateOrderStatus::unauthorized();
        
        Gate::authorize('update-status', $order);

        if (UpdateOrderStatus::checkSequence($order->status, $status)) {
            UpdateOrderStatus::update($order, $status);
            UpdateOrderStatus::succeed($status);
            UpdateOrderStatus::publish($order);
        } else {
            UpdateOrderStatus::failed($status);
        }

        return app(UpdateOrderStatusResponse::class);
    }

    /**
     * Cancel the given order.
     * 
     * @param  string  $token
     * @return \App\Interfaces\Responses\UpdateOrderStatusResponse
     */
    public function cancel(string $token): UpdateOrderStatusResponse
    {
        $order = Order::where('token', $token)->firstOrFail();
        $status = 'canceled';

        if (in_array($order->status, ['canceled', 'rejected', 'delivered'])) {
            UpdateOrderStatus::failed($status);
        } else {
            UpdateOrderStatus::update($order, $status);
            UpdateOrderStatus::succeed($status);
        }

        return app(UpdateOrderStatusResponse::class);
    }

    /**
     * Send a link to the customer where they can view all of their orders from.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Interfaces\Responses\RequestMyOrdersResponse
     */
    public function requestMyOrders(Request $request): RequestMyOrdersResponse
    {
        $email = $request->input('email');
        $order = Order::where('customer->email', $email)->first();

        if (null === $order) {
            RequestMyOrders::failed();
        } else {
            $customer = (object) json_decode($order->customer);

            RequestMyOrders::notify($order, $customer);
            requestMyOrders::succeed();
        }
        
        return app(RequestMyOrdersResponse::class);
    }
}
