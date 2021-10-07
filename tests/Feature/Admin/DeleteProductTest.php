<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\{User, Product};

class DeleteProductTest extends TestCase
{
    public function testAdminCanDeleteProduct()
    {
        $admin = User::factory()->admin()->make();
        $product = Product::factory()->create();

        $this->actingAs($admin);

        $this->get(route('product.delete', $product->id))->assertSessionHas('status', 'success');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        return $product;
    }

    /**
     * @depends testAdminCanDeleteProduct
     */
    public function testAdminCannotDeleteNonExistentProduct($nonExistentProduct)
    {
        $admin = User::factory()->admin()->make();

        $this->actingAs($admin);

        $this->get(route('product.delete', $nonExistentProduct->id))
            ->assertSessionHas(['status' => 'error', 'reason' => 'Not Found']);
    }
}
