<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\{Member, Product};

final class CreateProductTest extends TestCase
{   
    public function testAdminCanCreateValidProduct(): array
    {
        $admin = Member::factory()->admin()->create();
        $form = [
            'name' => Str::random(10),
            'price' => random_int(1, 40000)
        ];
        $valid_prices = [1, 0.1, 100000, 0.01];

        $this->actingAs($admin);
        $this->from(route('product.create-view'))
            ->post(route('product.create', $form))
            ->assertRedirect(route('products'))
            ->assertSessionHas('status', 'success');
        $this->assertDatabaseHas('products', $form);

        foreach ($valid_prices as $valid_price) {
            $form['name'] = Str::random(10);
            $form['price'] = $valid_price;

            $this->post(route('product.create', $form))
                ->assertSessionHas('status', 'success');
        }

        $form['name'] = Str::random(10);

        return $form;
    }

    /** @depends testAdminCanCreateValidProduct */
    public function testAdminCannotCreateInvalidProduct($form): void
    {
        $admin = Member::factory()->admin()->create();
        $invalid_prices = [-1, 0, -0.00000000001, -0.000000000001, 0.001, 'string', false, null, ''];

        $this->actingAs($admin);

        foreach ($invalid_prices as $invalid_price) {
            $form['price'] = $invalid_price;

            $this->post(route('product.create', $form))->assertSessionHasErrors('price');
        }

        $this->post(route('product.create'))->assertSessionHasErrors(['name', 'price']);
        $this->post(route('product.create', ['name' => Str::random(10)]))->assertSessionHasErrors('price');
        $this->assertDatabaseMissing('products', ['name' => $form['name']]);
    }
}
