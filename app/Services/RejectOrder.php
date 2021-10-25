<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Notification;
use App\Models\Order;
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

        $customer = (object) json_decode($order->customer_details);

        Notification::route('mail', $customer->email)
            ->notify(new OrderRejected($order, $customer));

        $response = [
            'status' => 'success',
            'message' => __('order.rejected'),
        ];

        $this->createResponse(RejectOrderResponse::class, $response);
    }
}