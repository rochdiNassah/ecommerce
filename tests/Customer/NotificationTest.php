<?php declare(strict_types=1);

namespace Tests\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use Tests\TestCase;
use App\Models\{User, Order, Product};
use App\Notifications\{OrderRejected, OrderDispatched};

final class NotificationTest extends TestCase
{
    /** @return void */
    public function testIsNotifiedWhenTheirOrderIsRejected(): void
    {
        Notification::fake();

        $order = Order::factory()->pending()->create();
        
        $this->actingAs($this->dispatcher)->get(route('order.reject', $order->id));

        Notification::assertSentTo(app(AnonymousNotifiable::class), OrderRejected::class);
    }

    /** @return void */
    public function testIsNotifiedWhenTheirOrderIsDispatched(): void
    {
        Notification::fake();

        $order = Order::factory()->pending()->create();
        $form = [
            'order_id' => $order->id,
            'delivery_driver_id' => $this->delivery_driver->id
        ];
        
        $this->actingAs($this->dispatcher)->post(route('order.dispatch'), $form);

        Notification::assertSentTo(app(AnonymousNotifiable::class), OrderDispatched::class);
    }

    /** @return void */
    public function setUp(): void
    {
        parent::setUp();

        $this->dispatcher = User::factory()->create(['role' => 'dispatcher']);
        $this->delivery_driver = User::factory()->create(['role' => 'delivery_driver']);
    }
}