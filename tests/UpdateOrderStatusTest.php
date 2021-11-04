<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Auth;
use App\Models\{Member, Order};

final class UpdateOrderStatusTest extends TestCase
{
    public function testOrderIsUpdatedInTheCorrectSequence(): void
    {
        $order = Order::factory()->dispatched(Auth::id())->create();

        $this->get(route('order.update-status', ['orderId' => $order->id, 'status' => 'shipped']))
            ->assertSessionHas('status', 'success');
        
        $invalidStatus = ['shipped', 'dispatched', 'foo', ' ', -1 , 0, 1];
        foreach ($invalidStatus as $status) {
            $this->get(route('order.update-status', ['orderId' => $order->id, 'status' => $status]))
                ->assertSessionHas('status', 'error');
        }

        $this->get(route('order.update-status', ['orderId' => $order->id, 'status' => 'delivered']))
            ->assertSessionHas('status', 'success');
        
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'delivered']);
    }

    public function testDeliveryDriverCannotUpdateAnOrderNotDispatchedToThem()
    {
        $delivery_driver = Member::factory()->deliveryDriver()->create();
        $order = Order::factory()->dispatched($delivery_driver->id)->create();

        $this->get(route('order.update-status', ['orderId' => $order->id, 'status' => 'shipped']))
            ->assertSessionHas('reason', 'Unauthorized');
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'dispatched']);

        $this->actingAs($delivery_driver);
        $this->get(route('order.update-status', ['orderId' => $order->id, 'status' => 'shipped']))
            ->assertSessionHas('status', 'success');
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'shipped']);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Member::factory()->deliveryDriver()->create());
    }
}