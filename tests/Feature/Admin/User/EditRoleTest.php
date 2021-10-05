<?php

namespace Tests\Feature\Admin\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class EditRoleTest extends TestCase
{
    public function testAdminCanUpgradeAndDowngradeUser()
    {
        $target = User::factory()->create();
        $admin = User::factory()->admin()->make();
        
        $this->actingAs($admin);

        $roles = ['dispatcher', 'delivery_driver'];

        foreach ($roles as $role) {
            $this->from(route('user.update-role-screen', $target->id))
                ->post(route('user.update-role', ['id' => $target->id, 'role' => $role]))
                ->assertRedirect(route('users'))
                ->assertSessionHas('status', 'success');

            $this->assertDatabaseHas('users', [
                'id' => $target->id,
                'role' => $role
            ]);       
        }
    }

    public function testAdminCannotBeDowngradedExceptByTheSuperAdmin()
    {
        $target = User::factory()->admin()->create();
        $admin = User::factory()->admin()->create();
        $superAdmin = User::factory()->superAdmin()->make();

        $this->actingAs($admin);

        $roles = ['dispatcher', 'delivery_driver'];

        foreach ($roles as $role) {
            $this->from(route('user.update-role-screen', $target->id))
                ->post(route('user.update-role', ['id' => $target->id, 'role' => $role]))
                ->assertRedirect(route('users'))
                ->assertSessionHas('reason', 'Unauthorized');

            $this->assertDatabaseHas('users', [
                'id' => $target->id,
                'role' => 'admin'
            ]);       
        }

        $data = ['id' => $target->id, 'role' => 'dispatcher'];

        $this->actingAs($superAdmin);
        $this->post(route('user.update-role', $data));
        $this->assertDatabaseHas('users', $data);
    }

    public function testCannotDowngradeSuperAdmin()
    {
        $admin = User::factory()->admin()->create();
        $superAdmin = User::factory()->superAdmin()->make();

        $data = ['id' => $superAdmin->id, 'role' => 'dispatcher'];

        $this->actingAs($admin);
        $this->post(route('user.update-role', $data));

        $this->actingAs($superAdmin);
        $this->post(route('user.update-role', $data));

        $this->assertDatabaseMissing('users', $data);
    }

    public function testAdminCannotUpgradeOrDowngradeNonExistentUser()
    {
        $admin = User::factory()->admin()->make();
        $user = User::factory()->create();

        $user->delete();

        $this->actingAs($admin)
            ->post(route('user.update-role', ['id' => $user->id, 'role' => 'dispatcher']))
            ->assertRedirect(route('users'))
            ->assertSessionHas('reason', 'Not Found');
    }
}
