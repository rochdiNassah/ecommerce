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
            ->assertSessionHas(['status' => 'success', 'message' => __('product.created')]);

        $this->assertDatabaseHas('products', $form);
    }
}
