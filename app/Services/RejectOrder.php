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
     * @param  int  $dispatcher_id
     * @return void
     */
    public static function reject($order, $dispatcher_id): void
    {
        $order->status = 'rejected';
        $order->dispatcher_id = $dispatcher_id;

        $order->save();

        $customer = (object) json_decode($order->customer);

        Notification::route('mail', $customer->email)
            ->notify(new OrderRejected($order, $customer));
    }

    /**
     * Order rejected successfully.
     * 
     * @return void
     */
    public static function succeed(): void
    {
        $response = [
            'status' => 'success',
            'message' => __('order.rejected')
        ];

        self::createResponse(RejectOrderResponse::class, $response);
    }

    /**
     * Attempted to reject a non-pending order.
     * 
     * @param  string  $orderStatus
     * @return void
     */
    public static function isNotPending(string $orderStatus): void
    {
        $response = [
            'status' => 'warning',
            'message' => "This order is already {$orderStatus}.",
        ];

        self::createResponse(RejectOrderResponse::class, $response);
    }
}