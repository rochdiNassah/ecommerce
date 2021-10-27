<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;
use App\Models\{User, Product};

final class MiddlewareTest extends TestCase
{
    /**
     * Assert that non-admin members cannot perform administrative actions.
     * 
     * @return array
     */
    public function testNonAdminCannotPerformAdministrativeActions(): array
    {
        $user = User::factory()->pending()->create();
        $product = Product::factory()->create();
        $sequence = new Sequence(
            ['role' => 'delivery_driver'],
            ['role' => 'dispatcher'],
            ['role' => '']
        );
        $members = User::factory()->count(3)->state($sequence)->create();

        foreach ($members as $member) {
            $this->actingAs($member);
            $this->get(route('users'))->assertSessionHas('reason', 'Unauthorized');
            $this->get(route('user.approve', $user->id))->assertSessionHas('reason', 'Unauthorized');
            $this->get(route('user.update-role-view', $user->id))->assertSessionHas('reason', 'Unauthorized');
            $this->post(route('user.update-role', ['id' => $user->id, 'role' => 'dispatcher']))->assertSessionHas('reason', 'Unauthorized');
            $this->get(route('products'))->assertSessionHas('reason', 'Unauthorized');
            $this->get(route('product.create-view'))->assertSessionHas('reason', 'Unauthorized');
            $this->post(route('product.create'))->assertSessionHas('reason', 'Unauthorized');
            $this->get(route('product.delete', $product->id))->assertSessionHas('reason', 'Unauthorized');
        }

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'delivery_driver',
            'status' => 'pending'
        ]);
        $this->assertDatabaseHas('products', ['id' => $product->id,]);

        return ['user' => $user, 'product' => $product];
    }

    /**
     * Assert that admins can perform administrative actions.
     * 
     * @depends testNonAdminCannotPerformAdministrativeActions
     * 
     * @param  array  $models
     * @return void
     */
    public function testAdminCanPerformAdministrativeActions($models): void
    {
        extract($models);

        $this->actingAs(User::factory()->admin()->make());
        $this->get(route('users'))->assertSuccessful();
        $this->get(route('user.approve', $user->id))->assertSessionHas('status', 'success');
        $this->get(route('user.update-role-view', $user->id))->assertSuccessful();
        $this->post(route('user.update-role', ['id' => $user->id, 'role' => 'dispatcher']))->assertSessionHas('reason', 'Upgraded');
        $this->get(route('products'))->assertSuccessful();
        $this->get(route('product.create-view'))->assertSuccessful();
        $this->post(route('product.create'), ['name' => Str::random(10), 'price' => '4.00'])->assertSessionHas('status', 'success');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name
        ]);
        $this->get(route('product.delete', $product->id));
        $this->assertDatabaseMissing('products', ['id' => $product->id,]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'dispatcher',
            'status' => 'active'
        ]);
        
        $this->get(route('user.delete', $user->id))->assertSessionHas('status', 'success');
        $this->assertDeleted($user);
    }
}
