<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Responses\UpdateOrderStatusResponse;
use App\Http\Responses\UnauthorizedResponse;

class UpdateOrderStatus extends BaseService
{
    /**
     * Check if the attempted update is in the correct sequence.
     * 
     * @param  string  $current_status
     * @param  string  $new_status
     * @return bool
     */
    public static function checkSequence(string $current_status, string $new_status): bool
    {
        $sequence = ['pending', 'rejected', 'canceled', 'dispatched', 'shipped', 'delivered'];
        $current_status = array_search($current_status, $sequence);
        $new_status = array_search($new_status, $sequence);

        return 1 === ($new_status - $current_status)
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
            'message' => __("Order is {$status} successfully.")
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