<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Notification;
use App\Models\Order;
use App\Notifications\OrderPlaced;
use App\Interfaces\Responses\PlaceOrderResponse;

class PlaceOrder extends BaseService
{
    private $customer;
    private $data;
    private $token;
    private $productId;

    /**
     * Store the order.
     * 
     * @return bool
     */
    public function store(): bool
    {
        Order::create($this->data);

        return true;
    }

    /**
     * Notify the customer.
     * 
     * @return bool
     */
    public function notifyCustomer(): bool
    {
        // TODO: Rollback on failure.

        $order = [
            'token' => $this->token,
            'customer' => $this->customer
        ];

        Notification::route('mail', $this->customer->email)
            ->notify(new OrderPlaced($order));

        return true;
    }

    /**
     * Prepare data to be stored.
     * 
     * @return void
     */
    public function prepareData(): void
    {
        $this->customer = (object) $this->request->only([
            'fullname', 'email', 'phone_number', 'address'
        ]);
        $this->token = bin2hex(openssl_random_pseudo_bytes(64));
        $this->data = [
            'customer' => json_encode($this->customer),
            'token' => $this->token,
            'product_id' => $this->request['product_id']
        ];
    }
    
    /**
     * Order creation succeed.
     * 
     * @return void
     */
    public function succeed(): void
    {
        $response = [
            'status' => 'success',
            'message' => __('order.placed'),
            'redirect_to' => route('home')
        ];

        $this->createResponse(PlaceOrderResponse::class, $response);
    }

    /**
     * Order creation failed.
     * 
     * @return void
     */
    public function failed(): void
    {
        $response = [
            'status' => 'error',
            'message' => __('global.failed')
        ];

        $this->createResponse(PlaceOrderResponse::class, $response);
    }
}