<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Notification;
use App\Models\Order;
use App\Notifications\OrderPlaced;
use App\Interfaces\Responses\PlaceOrderResponse;

class PlaceOrder extends BaseService
{
    /**
     * Store the given order.
     * 
     * @param  array  $validated
     * @return mixed
     */
    public static function store(array $validated)
    {
        $validated['token'] = bin2hex(openssl_random_pseudo_bytes(64));

        return Order::create($validated);
    }

    /**
     * Notify the customer that their order is placed.
     * 
     * @param  \App\Models\Order  $order
     * @return void
     */
    public static function notifyCustomer($order): void
    {
        $customer = (object) json_decode($order->customer);

        Notification::route('mail', $customer->email)
            ->notify(new OrderPlaced($order, $customer));
    }
    
    /**
     * Order created successfully.
     * 
     * @return void
     */
    public static function succeed(): void
    {
        $response = [
            'status' => 'success',
            'message' => __('order.placed'),
            'redirect_to' => route('home')
        ];

        self::createResponse(PlaceOrderResponse::class, $response);
    }

    /**
     * Failed to create the order.
     * 
     * @return void
     */
    public static function failed(): void
    {
        $response = [
            'status' => 'error',
            'message' => __('global.failed')
        ];

        self::createResponse(PlaceOrderResponse::class, $response);
    }
}