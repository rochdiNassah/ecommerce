<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Responses\DispatchOrderResponse;
use Illuminate\Support\Facades\{Auth, Notification};
use App\Notifications\OrderDispatched;

class DispatchOrder extends BaseService
{
    /**
     * Dispatch the given order to the given delivery driver.
     * 
     * @param  \App\Models\Order  $order
     * @param  \App\Models\User  $delivery_driver
     * @return void
     */
    public function dispatch($order, $delivery_driver): void
    {
        $order->status = 'dispatched';
        $order->delivery_driver_id = $delivery_driver->id;
        $order->dispatcher_id = Auth::id();

        $order->save();

        $customer = (object) json_decode($order->customer);

        Notification::route('mail', $customer->email)
            ->notify(new OrderDispatched($order, $customer));

        $response = [
            'status' => 'success',
            'message' => "Order dispatched to {$delivery_driver->fullname} successfully.",
            'redirect_to' => route('dashboard')
        ];

        $this->createResponse(DispatchOrderResponse::class, $response);
    }
}