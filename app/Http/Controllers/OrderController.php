<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{PlaceOrderRequest, RejectOrderRequest};
use App\Models\Order;
use App\Services\{PlaceOrder, RejectOrder};
use App\Interfaces\Responses\PlaceOrderResponse;
use App\Interfaces\Responses\RejectOrderResponse;

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

        $service->reject($order);

        return app(RejectOrderResponse::class);
    }
}
