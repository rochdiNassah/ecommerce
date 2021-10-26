<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Notification;
use App\Models\Order;
use App\Notifications\OrderPlaced;
use App\Interfaces\Responses\PlaceOrderResponse;

class PlaceOrder extends BaseService
{
    /**
     * @param  array  $validated
     * @return mixed
     */
    public static function store(array $validated)
    {
        $validated['token'] = bin2hex(openssl_random_pseudo_bytes(64));

        return Order::create($validated);
    }

    /**
     * Notify the customer.
     * 
     * @param  \App\Models\Order  $order
     * @return void
     */
    public static function notifyCustomer($order): void
    {
        $customer = (object) json_decode($order->customer);

        Notification::route('mail', $customer->email)
            ->notify(new OrderPlaced($order));
    }
    
    /**
     * Order creation succeed.
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
     * Order creation failed.
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