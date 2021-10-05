<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;
use App\Models\User;

class MiddlewareTest extends TestCase
{
    public function testNonAdminCannotPerformAdministrativeActions()
    {
        $sequence = new Sequence(
            ['role' => 'delivery_driver'],
            ['role' => 'dispatcher'],
            ['role' => '']
        );

        // The user that we will perform administrative actions against.
        $user = User::factory()->pending()->create();

        $members = User::factory()->count(3)->state($sequence)->create();

        foreach ($members as $member) {
            $this->actingAs($member);

            $this->get(route('users'))->assertSessionHas('reason', 'Unauthorized');
            $this->get(route('products'))->assertSessionHas('reason', 'Unauthorized');
            $this->get(route('user.approve', $user->id))->assertSessionHas('reason', 'Unauthorized');
            $this->get(route('user.update-role-screen', $user->id))->assertSessionHas('reason', 'Unauthorized');
            $this->post(route('user.update-role', ['id' => $user->id, 'role' => 'dispatcher']))
                ->assertSessionHas('reason', 'Unauthorized');
        }

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'delivery_driver',
            'status' => 'pending'
        ]);

        return $user;
    }

    /**
     * @depends testNonAdminCannotPerformAdministrativeActions
     */
    public function testAdminCanPerformAdministrativeActions($user)
    {
        $admin = User::factory()->admin()->make();

        $this->actingAs($admin);

        $this->get(route('users'))->assertSuccessful();
        $this->get(route('products'))->assertSuccessful();
        $this->get(route('user.approve', $user->id))->assertSessionHas('status', 'success');
        $this->get(route('user.update-role-screen', $user->id))->assertSuccessful();
        $this->post(route('user.update-role', ['id' => $user->id, 'role' => 'dispatcher']))
            ->assertSessionHas('reason', 'Upgraded');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'dispatcher',
            'status' => 'active'
        ]);

        $this->get(route('user.delete', $user->id))->assertSessionHas('status', 'success');

        $this->assertDeleted($user);
    }
}
