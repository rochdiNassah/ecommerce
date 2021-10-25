<?php declare(strict_types=1);

namespace Tests\Dispatcher;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\{User, Order};

final class RejectOrderTest extends TestCase
{
    /** @return void */
    public function testCanRejectPendingOrder(): void
    {
        $order = Order::factory()->pending()->create();

        $this->get(route('order.reject', $order->id))->assertSessionHas('status', 'success');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'rejected'
        ]);
    }

    /** @return void */
    public function testCannotRejectNonPendingOrder(): void
    {
        $rejected = Order::factory()->rejected()->create();
        $dispatched = Order::factory()->dispatched()->create();
        $delivered = Order::factory()->delivered()->create();
        $orders = [$rejected, $dispatched, $delivered];
        $statuses = ['rejected', 'dispatched', 'delivered'];

        foreach ($orders as $key => $order) {
            $this->get(route('order.reject', $order->id))->assertSessionHas('status', 'warning');

            $this->assertDatabaseHas('orders', [
                'id' => $order->id,
                'status' => $statuses[$key]
            ]);
        }
    }

    /** @return void */
    public function testCannotRejectNonExistentOrder(): void
    {
        $this->get(route('order.reject', PHP_INT_MAX))
            ->assertSessionHas(['status' => 'error', 'reason' => 'Not Found']);
    }

    /**
     * Actings as dispatcher.
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->make(['role' => 'dispatcher']));
    }
}