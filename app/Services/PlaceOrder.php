<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Notification;
use App\Models\Order;
use App\Notifications\OrderPlaced;

class PlaceOrder extends Service
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
        $this->token = bin2hex(openssl_random_pseudo_bytes(128));
        $this->data = [
            'customer_details' => json_encode($this->customer),
            'token' => $this->token,
            'product' => $this->request['product_id']
        ];
    }
    
    /**
     * Order creation succeed.
     * 
     * @return void
     */
    public function success(): void
    {
        $this->response = [
            'status' => 'success',
            'message' => __('order.placed')
        ];
    }

    /**
     * Order creation failed.
     * 
     * @return void
     */
    public function fail(): void
    {
        $this->response = [
            'status' => 'error',
            'message' => __('global.failed')
        ];
    }
}