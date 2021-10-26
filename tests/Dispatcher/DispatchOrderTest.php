<?php declare(strict_types=1);

namespace Tests\Dispatcher;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\{User, Order};

final class DispatchOrderTest extends TestCase
{
    /** @return void */
    public function testCanDispatchPendingOrder(): void
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

    /** @return void */
    public function testCannotDispatchOrderToNonDeliveryDriverMember(): void
    {
        $order = Order::factory()->create();
        $dispatcher = User::factory()->make(['role' => 'dispatcher']);
        $sequence = new Sequence(
            ['role' => 'admin'],
            ['role' => 'dispatcher'],
            ['role' => '']
        );
        $members = User::factory()->count(2)->state($sequence)->create();

        foreach ($members as $member) {
            $this->post(route('order.dispatch'), [
                'order_id' => $order->id,
                'delivery_driver_id' => $member->id
            ])->assertSessionHas('status', 'warning');
        }
    }

    /** @return void */
    public function testCannotDispatchNonPendingOrder(): void
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

    /**
     * Actings as dispatcher.
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => 'dispatcher']));
    }
}