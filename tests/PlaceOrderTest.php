<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use Tests\TestCase;
use App\Models\{Order, Product};
use App\Notifications\OrderPlaced;

final class PlaceOrderTest extends TestCase
{
    /** @var \App\Models\User */
    private $dispatcher;

    /** @var \App\Models\User */
    private $delivery_driver;

    /** @return void */
    public function testCustomerCanPlaceValidOrder(): void
    {
        Notification::fake();

        $product = Product::factory()->create();
        $form = [
            'email' => Str::random(10).'@foo.bar',
            'address' => 'Corge, grault',
            'fullname' => Str::random(10),
            'phone_number' => str_repeat('0', 10),
            'product_id' => $product->id
        ];
        $customer = str_replace(
            ['":"', '","'],
            ['": "', '", "'],
            json_encode(array_slice($form, 0, -1), 1)
        );

        $this->post(route('order.create'), $form)->assertSessionHas('status', 'success');

        $order = Order::where('product_id', $product->id)->first();

        $this->assertEquals($order->customer, $customer);

        Notification::assertSentTo(app(AnonymousNotifiable::class), OrderPlaced::class);
    }
}