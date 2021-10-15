<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PlaceOrderRequest;
use App\Models\Order;
use App\Services\PlaceOrder;

class OrderController extends Controller
{
    /**
     * Place an order.
     * 
     * @param  \App\Http\Requests\PlaceOrderRequest  $request
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function create(PlaceOrderRequest $request)
    {
        $responsable = app(PlaceOrder::class, ['request' => $request]);

        $responsable->prepareData();
        $responsable->store();

        $responsable->notifyCustomer()
            ? $responsable->success()
            : $responsable->fail();

        return $responsable;
    }
}
