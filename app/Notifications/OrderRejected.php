<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderRejected extends Notification implements ShouldQueue
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
                    ->greeting("Hello {$this->customer->fullname}, and thank you for your order.")
                    ->line('Unfortunately, your order is rejected due to an invalid or missing details.')
                    ->action('View Order', route('order.track-view', $this->order->token))
                    ->action('Place Your Order Again!', url(route('order.create-view', $this->order->id)));
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
