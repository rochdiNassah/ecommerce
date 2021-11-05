<?php declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification implements ShouldQueue
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
    public function toMail()
    {
        return (new MailMessage)
                    ->greeting("Hello {$this->customer->fullname}, and thank you for your order.")
                    ->line('We\'ve received your order successfully and we would keep you updated with your order\'s status whenever we update it.')
                    ->line('Click the below button if you want to track or cancel your order.')
                    ->action('View Order', url(route('order.track-view', $this->order->token)));
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
