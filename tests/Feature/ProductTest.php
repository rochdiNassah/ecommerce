<?php declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\{Product, User};

final class ProductTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->admin()->make();

        $this->actingAs($admin);
    }

    /**
     * Assert that a product can be created.
     * 
     * @return void
     */
    public function testIsCreatable(): void
    {
        $form = [
            'name' => Str::random(8),
            'price' => random_int(8, 4096)
        ];

        $this->post(route('product.create'), $form);
        
        $this->assertDatabaseHas('products', $form);
    }

    /**
     * Assert that a product can be deleted.
     * 
     * @return void
     */
    public function testIsDeletable(): void
    {
        $product = Product::factory()->create();

        $this->get(route('product.delete', $product->id));

        $this->assertDatabaseMissing('products', collect($product)->toArray());
    }
}
