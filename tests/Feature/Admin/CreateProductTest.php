<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\{User, Product};

class CreateProductTest extends TestCase
{   
    public function testAdminCanCreateValidProduct()
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin);

        $form = [
            'name' => Str::random(10),
            'description' => Str::random(100),
            'price' => random_int(1, 40000)
        ];

        $this->from(route('product.create-view'))
            ->post(route('product.create', $form))
            ->assertRedirect(route('products'))
            ->assertSessionHas('status', 'success');

        $this->assertDatabaseHas('products', $form);

        $validPrices = [1, 0.1, 100000, 0.00001];

        foreach ($validPrices as $validPrice) {
            $form['name'] = Str::random(10);
            $form['price'] = $validPrice;

            $this->post(route('product.create', $form))
                ->assertSessionHas('status', 'success');
        }

        $form['name'] = Str::random(10);

        return $form;
    }

    /**
     * @depends testAdminCanCreateValidProduct
     */
    public function testAdminCannotCreateInvalidProduct($form)
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin);

        $invalidPrices = [-1, 0, -0.00000000001, -0.000000000001, 0.000000000001, 'string', false, null, ''];

        foreach ($invalidPrices as $invalidPrice) {
            $form['price'] = $invalidPrice;

            $this->post(route('product.create', $form))->assertSessionHasErrors('price');
        }

        $this->post(route('product.create'))->assertSessionHasErrors(['name', 'description', 'price']);
        $this->post(route('product.create', ['name' => Str::random(10)]))->assertSessionHasErrors(['price', 'description']);

        $this->assertDatabaseMissing('products', ['name' => $form['name']]);
    }
}
