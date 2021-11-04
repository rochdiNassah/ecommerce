<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\{User, Product};

final class DeleteProductTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->make());
    }

    public function testAdminCanDeleteProduct(): Product
    {
        $product = Product::factory()->create();

        $this->get(route('product.delete', $product->id))->assertSessionHas('status', 'success');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        return $product;
    }

    /** @depends testAdminCanDeleteProduct */
    public function testAdminCannotDeleteNonExistentProduct($nonExistentProduct): void
    {
        $this->get(route('product.delete', $nonExistentProduct->id))
            ->assertSessionHas(['status' => 'error', 'reason' => 'Not Found']);
    }
}
