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
        
        $this->succeed($delivery_driver);
    }

    /**
     * Order dispatched successfully.
     * 
     * @param  \App\Models\User  $delivery_driver
     * @return void
     */
    private function succeed($delivery_driver): void
    {
        $response = [
            'status' => 'success',
            'message' => "Order dispatched to {$delivery_driver->fullname} successfully.",
            'redirect_to' => route('dashboard')
        ];

        $this->createResponse(DispatchOrderResponse::class, $response);
    }

    /** @return void */
    public function isNotDeliveryDriver(): void
    {
        $response = [
            'status' => 'warning',
            'message' => 'Order can be dispatched to delivery drivers only.'
        ];

        $this->createResponse(DispatchOrderResponse::class, $response);
    }

    /**
     * Attempted to dispatch a non-pending order.
     * 
     * @param  string  $orderStatus
     * @return void
     */
    public function isNotPending(string $orderStatus): void
    {
        $response = [
            'status' => 'warning',
            'message' => "This order is already {$orderStatus}.",
            'redirect_to' => route('dashboard')
        ];

        $this->createResponse(DispatchOrderResponse::class, $response);
    }
}