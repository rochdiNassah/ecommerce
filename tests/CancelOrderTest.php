<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\{User, Order};

final class CancelOrderTest extends TestCase
{
    public function testCustomerCanCancelTheirOrder(): void
    {
        $sequence = new Sequence(
          ['status' => 'pending'],
          ['status' => 'dispatched'],
          ['status' => 'shipped']
        );
        $orders = Order::factory()->count(3)->state($sequence)->create();

        foreach ($orders as $order) {
            $this->get(route('order.cancel', $order->token))
                ->assertSessionHas('status', 'success');
            $this->assertDatabaseHas('orders', [
                'id' => $order->id,
                'token' => $order->token,
                'status' => 'canceled'
            ]);
        }
    }

    public function testCustomerCannotCancelTheirUncancelableOrder(): void
    {
        $sequence = new Sequence(
            ['status' => 'rejected'],
            ['status' => 'canceled'],
            ['status' => 'delivered']
        );
        $orders = Order::factory()->count(3)->state($sequence)->create();

        foreach ($orders as $order) {
            $this->get(route('order.cancel', $order->token))
                ->assertSessionHas('status', 'error');
            $this->assertDatabaseHas('orders', [
                'id' => $order->id,
                'token' => $order->token,
                'status' => $order->status
            ]);
        }
    }
}