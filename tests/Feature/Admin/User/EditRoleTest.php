<?php

namespace Tests\Feature\Admin\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class EditRoleTest extends TestCase
{
    public function testAdminCanUpgradeUser()
    {
        $target = User::factory()->create();
        $admin = User::factory()->make(['role' => 'admin']);
        $role = 'admin';

        $this->actingAs($admin)
            ->from(route('user.update-role-screen', $target->id))
            ->post(route('user.update-role', ['id' => $target->id, 'role' => $role]))
            ->assertRedirect(route('users'))
            ->assertSessionHas([
                'status' => 'success',
                'reason' => 'Upgraded'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'role' => $role
        ]);        
    }

    public function testAdminCanDowngradeUser()
    {
        $target = User::factory()->create();
        $admin = User::factory()->make(['role' => 'admin']);
        $role = 'delivery_driver';

        $this->actingAs($admin)
            ->from(route('user.update-role-screen', $target->id))
            ->post(route('user.update-role', ['id' => $target->id, 'role' => $role]))
            ->assertRedirect(route('users'))
            ->assertSessionHas([
                'status' => 'success',
                'reason' => 'Downgraded'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'role' => $role
        ]);  
    }

    public function testAdminCannotBeDowngradedExceptByTheSuperAdminOrThemselves()
    {
        $target = User::factory()->create(['is_super_admin' => false, 'role' => 'admin']);
        $admin = User::factory()->make(['role' => 'admin']);
        $role = 'dispatcher';

        $this->actingAs($admin)
            ->from(route('user.update-role-screen', $target->id))
            ->post(route('user.update-role', ['id' => $target->id, 'role' => $role]))
            ->assertRedirect(route('users'))
            ->assertSessionHas([
                'status' => 'error',
                'reason' => 'Unauthorized'
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $target->id,
            'role' => $role
        ]); 
    }

    public function testSuperAdminCanBeUpgraded()
    {
        $target = User::factory()->create(['is_super_admin' => false]);
        $admin = User::factory()->make(['role' => 'admin']);
        $role = 'admin';

        $this->actingAs($admin)
            ->from(route('user.update-role-screen', $target->id))
            ->post(route('user.update-role', ['id' => $target->id, 'role' => $role]))
            ->assertRedirect(route('users'))
            ->assertSessionHas([
                'status' => 'success',
                'reason' => 'Upgraded'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'role' => $role
        ]); 
    }

    public function testAdminCannotUpgradeOrDowngradeNonExistentUser()
    {
        $admin = User::factory()->make(['role' => 'admin']);
        $role = 'admin';

        $this->actingAs($admin)
            ->post(route('user.update-role', ['id' => -1, 'role' => $role]))
            ->assertRedirect(route('users'))
            ->assertSessionHas([
                'status' => 'error',
                'reason' => 'Not Found'
            ]);
    }
}
