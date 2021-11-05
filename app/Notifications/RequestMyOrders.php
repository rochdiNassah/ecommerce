<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestMyOrders extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var \App\Models\Order */
    protected $order;

    /** @var object */
    protected $customer;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Order  $order
     * @param  object  $customer
     */
    public function __construct($order, object $customer)
    {
        $this->order = $order;
        $this->customer = $customer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting("Hello {$this->customer->fullname}, and thank you for ordering from us.")
                    ->line('To view all of your orders, click the button below.')
                    ->action('View Orders', url(route('order.my-orders', ['email' => $this->customer->email, 'token' => $this->order->token])));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
