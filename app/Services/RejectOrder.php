<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderRejected;
use App\Interfaces\Responses\RejectOrderResponse;

class RejectOrder extends BaseService
{
    /**
     * Reject the given order and notify the customer.
     * 
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function reject($order): void
    {
        $order->status = 'rejected';

        $order->save();

        $customer = (object) json_decode($order->customer);

        Notification::route('mail', $customer->email)
            ->notify(new OrderRejected($order, $customer));

        $this->succeed();
    }

    /**
     * Rejected the order successfully.
     * 
     * @return void
     */
    public function succeed()
    {
        $response = [
            'status' => 'success',
            'message' => __('order.rejected')
        ];

        $this->createResponse(RejectOrderResponse::class, $response);
    }

    /**
     * Attempted to reject a non-pending order.
     * 
     * @param  string  $orderStatus
     * @return void
     */
    public function isNotPending(string $orderStatus): void
    {
        $response = [
            'status' => 'warning',
            'message' => "This order is already {$orderStatus}.",
        ];

        $this->createResponse(RejectOrderResponse::class, $response);
    }
}