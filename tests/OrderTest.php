<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\{Product, Order, User};

final class OrderTest extends TestCase
{
    /** @return void */
    public function testIsCreatable(): void
    {
        $product = Product::factory()->create();
        $form = [
            'email' => Str::random(10).'@foo.bar',
            'address' => 'Corge, grault',
            'fullname' => Str::random(10),
            'phone_number' => str_repeat('0', 10),
            'product_id' => $product->id
        ];
        $customer = str_replace(['":"', '","'], ['": "', '", "'], json_encode(array_slice($form, 0, -1), 1));

        $this->post(route('order.create'), $form);
        
        $order = Order::where('product_id', $product->id)->first();

        $this->assertEquals($order->customer, $customer);

        $this->order = $order;
    }

    /** @return void */
    public function testIsRejectable(): void
    {
        $order = Order::factory()->create();
        $dispatcher = User::factory()->make(['role' => 'dispatcher']);

        $this->actingAs($dispatcher);
        $this->get(route('order.reject', $order->id));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'rejected'
        ]);
    }
}
