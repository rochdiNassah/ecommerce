<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{PlaceOrderRequest, RejectOrderRequest};
use App\Models\Order;
use App\Services\PlaceOrder;

class OrderController extends Controller
{
    /**
     * Place an order.
     * 
     * @param  \App\Http\Requests\PlaceOrderRequest  $request
     * @return \App\Services\PlaceOrder
     */
    public function create(PlaceOrderRequest $request): PlaceOrder
    {
        $responsable = app(
            PlaceOrder::class,
            ['request' => $request]
        );

        $responsable->prepareData();
        $responsable->store();

        $responsable->notifyCustomer()
            ? $responsable->success()
            : $responsable->fail();
            
        return $responsable;
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
