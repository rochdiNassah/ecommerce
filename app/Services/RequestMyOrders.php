<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Notification;
use App\Notifications\RequestMyOrders as RequestMyOrdersNotification;
use App\Interfaces\Responses\RequestMyOrdersResponse;

class RequestMyOrders extends BaseService
{
    /**
     * Send a link to the customer where they can view all of their orders from.
     * 
     * @param  \App\Models\Order  $order
     * @param  object  $customer
     * @return void
     */
    public static function notify($order, object $customer): void
    {
        Notification::route('mail', $customer->email)
            ->notify(new RequestMyOrdersNotification($order, $customer));
    }

    /** @return void */
    public static function succeed(): void
    {
        $response = [
            'status' => 'success',
            'message' => __('We have sent a link to you. Please check your inbox.'),
            'redirect_to' => route('home')
        ];

        self::createResponse(RequestMyOrdersResponse::class, $response);
    }

    /** @return void */
    public static function failed(): void
    {
        $response = [
            'status' => 'warning',
            'message' => __('The email address you provided is not associated with any order.')
        ];

        self::createResponse(RequestMyOrdersResponse::class, $response);
    }
}