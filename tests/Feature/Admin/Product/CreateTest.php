<?php

namespace Tests\Feature\Admin\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\{User, Product};

class CreateTest extends TestCase
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

        $response = $this->from(route('product.create-view'))
            ->post(route('product.create', $form))
            ->assertRedirect(route('products'))
            ->assertSessionHas('status', 'success');

        $this->assertDatabaseHas('products', $form);

        return $form;
    }

    /**
     * @depends testAdminCanCreateValidProduct
     */
    public function testAdminCannotCreateInvalidProduct($form)
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin);
        
        $response = $this->post(route('product.create', $form))->assertSessionHasErrors('name');
        
        $form['name'] = Str::random(10);
        $form['price'] = -1;
        $response = $this->post(route('product.create', $form))->assertSessionHasErrors('price');

        $form['price'] = 0;
        $response = $this->post(route('product.create', $form))->assertSessionHasErrors('price');

        $form['price'] = -0.00000000001;
        $response = $this->post(route('product.create', $form))->assertSessionHasErrors('price');

        $form['price'] = -0.000000000001;
        $response = $this->post(route('product.create', $form))->assertSessionHasErrors('price');

        $form['price'] = 0.000000000001;
        $response = $this->post(route('product.create', $form))->assertSessionHasErrors('price');

        $this->assertDatabaseMissing('products', ['name' => $form['name']]);
    }
}
