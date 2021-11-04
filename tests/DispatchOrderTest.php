<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\{Member, Order};

final class DispatchOrderTest extends TestCase
{
    public function testDispatcherCanDispatchPendingOrder(): void
    {
        $order = Order::factory()->create();
        $deliver_driver = Member::factory()->create(['role' => 'delivery_driver']);

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
        $dispatcher = Member::factory()->make(['role' => 'dispatcher']);
        $sequence = new Sequence(
            ['role' => 'admin'],
            ['role' => 'dispatcher'],
            ['role' => '']
        );
        $members = Member::factory()->count(3)->state($sequence)->create();

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
        $dispatcher = Member::factory()->create(['role' => 'dispatcher']);
        $deliver_driver = Member::factory()->create(['role' => 'delivery_driver']);

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

        $this->actingAs(Member::factory()->dispatcher()->create());
    }
}