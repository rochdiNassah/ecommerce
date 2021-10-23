<?php declare(strict_types=1);

namespace Tests\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\{User, Product};

final class CreateProductTest extends TestCase
{   
    /** @return array */
    public function testAdminCanCreateValidProduct(): array
    {
        $admin = User::factory()->admin()->create();
        $form = [
            'name' => Str::random(10),
            'price' => random_int(1, 40000)
        ];
        $validPrices = [1, 0.1, 100000, 0.01];

        $this->actingAs($admin);
        $this->from(route('product.create-view'))
            ->post(route('product.create', $form))
            ->assertRedirect(route('products'))
            ->assertSessionHas('status', 'success');
        $this->assertDatabaseHas('products', $form);

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
     * 
     * @param  array  $form
     * @return void
     */
    public function testAdminCannotCreateInvalidProduct($form): void
    {
        $admin = User::factory()->admin()->create();
        $invalidPrices = [-1, 0, -0.00000000001, -0.000000000001, 0.001, 'string', false, null, ''];

        $this->actingAs($admin);

        foreach ($invalidPrices as $invalidPrice) {
            $form['price'] = $invalidPrice;

            $this->post(route('product.create', $form))->assertSessionHasErrors('price');
        }

        $this->post(route('product.create'))->assertSessionHasErrors(['name', 'price']);
        $this->post(route('product.create', ['name' => Str::random(10)]))->assertSessionHasErrors('price');
        $this->assertDatabaseMissing('products', ['name' => $form['name']]);
    }
}
