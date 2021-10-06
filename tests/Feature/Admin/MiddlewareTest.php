<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;
use App\Models\{User, Product};

class MiddlewareTest extends TestCase
{
    public function testNonAdminCannotPerformAdministrativeActions()
    {
        $sequence = new Sequence(
            ['role' => 'delivery_driver'],
            ['role' => 'dispatcher'],
            ['role' => '']
        );

        // The resource that we will perform administrative actions against.
        $user = User::factory()->pending()->create();
        $product = Product::factory()->create();

        $members = User::factory()->count(3)->state($sequence)->create();

        foreach ($members as $member) {
            $this->actingAs($member);

            // User
            $this->get(route('users'))->assertSessionHas('reason', 'Unauthorized');
            $this->get(route('user.approve', $user->id))->assertSessionHas('reason', 'Unauthorized');
            $this->get(route('user.update-role-view', $user->id))->assertSessionHas('reason', 'Unauthorized');
            $this->post(route('user.update-role', ['id' => $user->id, 'role' => 'dispatcher']))->assertSessionHas('reason', 'Unauthorized');

            // Product
            $this->get(route('products'))->assertSessionHas('reason', 'Unauthorized');
        }

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'delivery_driver',
            'status' => 'pending'
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name
        ]);

        return ['user' => $user, 'product' => $product];
    }

    /**
     * @depends testNonAdminCannotPerformAdministrativeActions
     */
    public function testAdminCanPerformAdministrativeActions($models)
    {
        extract($models);

        $admin = User::factory()->admin()->make();

        $this->actingAs($admin);

        // User
        $this->get(route('users'))->assertSuccessful();
        $this->get(route('user.approve', $user->id))->assertSessionHas('status', 'success');
        $this->get(route('user.update-role-view', $user->id))->assertSuccessful();
        $this->post(route('user.update-role', ['id' => $user->id, 'role' => 'dispatcher']))
            ->assertSessionHas('reason', 'Upgraded');

        // Product
        $this->get(route('products'))->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'dispatcher',
            'status' => 'active'
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name
        ]);

        $this->get(route('user.delete', $user->id))->assertSessionHas('status', 'success');

        $this->assertDeleted($user);
    }
}
