<?php declare(strict_types=1);

namespace Tests\Dispatcher;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\{User, Order};

final class DispatchOrderTest extends TestCase
{
    /** @return void */
    public function testCanDispatchPendingOrder(): void
    {
        $order = Order::factory()->create();
        $dispatcher = User::factory()->make(['role' => 'dispatcher']);
        $deliver_driver = User::factory()->create(['role' => 'delivery_driver']);

        $this->actingAs($dispatcher);
        $this->post(route('order.dispatch'), ['order_id' => $order->id, 'delivery_driver_id' => $deliver_driver->id]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'dispatcher_id' => $dispatcher->id,
            'delivery_driver_id' => $deliver_driver->id,
            'status' => 'dispatched'
        ]);
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