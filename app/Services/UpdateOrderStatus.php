<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Responses\UpdateOrderStatusResponse;
use App\Http\Responses\UnauthorizedResponse;

class UpdateOrderStatus extends BaseService
{
    /**
     * Check if the attempted update is in the correct sequence.
     * 
     * @param  string  $currentStatus
     * @param  string  $newStatus
     * @return bool
     */
    public static function checkSequence(string $currentStatus, string $newStatus): bool
    {
        $sequence = ['pending', 'rejected', 'canceled', 'dispatched', 'shipped', 'delivered'];
        $currentStatus = array_search($currentStatus, $sequence);
        $newStatus = array_search($newStatus, $sequence);

        return 1 === ($newStatus - $currentStatus)
            ? true
            : false;
    }

    /**
     * Update the given order's status.
     * 
     * @param  \App\Models\Order  $order
     * @param  string  $status
     * @return void
     */
    public static function update($order, string $status): void
    {
        $order->status = $status;

        $order->save();
    }

    /**
     * Order's status updated successfully.
     * 
     * @param  string  $status
     * @return void
     */
    public static function succeed(string $status): void
    {
        $response = [
            'status' => 'success',
            'message' => __("Order is now {$status}.")
        ];

        self::createResponse(UpdateOrderStatusResponse::class, $response);
    }

    /**
     * Failed to update the order's status.
     * 
     * @param  string  $status
     * @return void
     */
    public static function failed(string $status): void
    {
        $response = [
            'status' => 'error',
            'message' => "This order cannot be {$status}."
        ];

        self::createResponse(UpdateOrderStatusResponse::class, $response);
    }

    /** @return void */
    public static function unauthorized()
    {
        $response = [
            'status' => 'warning',
            'message' => 'You are not authorized to update this order.',
            'redirect_to' => route('dashboard')
        ];

        app()->singleton(UnauthorizedResponse::class, function ($app) use ($response) {
            return new UnauthorizedResponse($response);
        });
    }
}