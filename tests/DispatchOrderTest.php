<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\{User, Order};

final class DispatchOrderTest extends TestCase
{
    public function testDispatcherCanDispatchPendingOrder(): void
    {
        $order = Order::factory()->create();
        $deliver_driver = User::factory()->create(['role' => 'delivery_driver']);

        $this->post(route('order.dispatch'), ['order_id' => $order->id, 'delivery_driver_id' => $deliver_driver->id]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'dispatcher_id' => Auth::id(),
            'delivery_driver_id' => $deliver_driver->id,
            'status' => 'dispatched'
        ]);
    }

    public function testDispatcherCannotDispatchOrderToNonDeliveryDriverMember(): void
    {
        $order = Order::factory()->create();
        $dispatcher = User::factory()->make(['role' => 'dispatcher']);
        $sequence = new Sequence(
            ['role' => 'admin'],
            ['role' => 'dispatcher'],
            ['role' => '']
        );
        $members = User::factory()->count(3)->state($sequence)->create();

        foreach ($members as $member) {
            $this->post(route('order.dispatch'), [
                'order_id' => $order->id,
                'delivery_driver_id' => $member->id
            ])->assertSessionHas('status', 'warning');
        }
    }

    public function testDispatcherCannotDispatchNonPendingOrder(): void
    {
        $dispatched = Order::factory()->dispatched()->create();
        $dispatcher = User::factory()->create(['role' => 'dispatcher']);
        $deliver_driver = User::factory()->create(['role' => 'delivery_driver']);

        $this->post(route('order.dispatch'), ['order_id' => $dispatched->id, 'delivery_driver_id' => $deliver_driver->id])
            ->assertSessionHas('status', 'warning');
        $this->assertDatabaseMissing('orders', [
            'id' => $dispatched->id,
            'dispatcher_id' => $dispatcher->id
        ]);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => 'dispatcher']));
    }
}