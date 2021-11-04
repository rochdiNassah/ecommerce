<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use Tests\TestCase;
use App\Models\{Member, Order, Product};
use App\Notifications\{OrderRejected, OrderDispatched};

final class NotificationTest extends TestCase
{
    public function testCustomerIsNotifiedWhenTheirOrderIsRejected(): void
    {
        Notification::fake();

        $order = Order::factory()->pending()->create();
        
        $this->actingAs($this->dispatcher)->get(route('order.reject', $order->id));

        Notification::assertSentTo(app(AnonymousNotifiable::class), OrderRejected::class);
    }

    public function testCustomerIsNotifiedWhenTheirOrderIsDispatched(): void
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

    public function setUp(): void
    {
        parent::setUp();

        $this->dispatcher = Member::factory()->create(['role' => 'dispatcher']);
        $this->delivery_driver = Member::factory()->create(['role' => 'delivery_driver']);
    }
}