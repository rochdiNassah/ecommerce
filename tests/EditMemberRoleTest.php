<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

final class EditMemberRoleTest extends TestCase
{
    public function testAdminCanUpgradeAndDowngradeUser(): void
    {
        $target = User::factory()->create();
        $admin = User::factory()->admin()->make();
        $roles = ['dispatcher', 'delivery_driver'];

        $this->actingAs($admin);

        foreach ($roles as $role) {
            $this->from(route('user.update-role-view', $target->id))
                ->post(route('user.update-role', ['id' => $target->id, 'role' => $role]))
                ->assertRedirect(route('users'))
                ->assertSessionHas('status', 'success');
            $this->assertDatabaseHas('users', [
                'id' => $target->id,
                'role' => $role
            ]);       
        }
    }

    public function testAdminCannotBeDowngradedExceptByTheSuperAdmin(): void
    {
        $target = User::factory()->admin()->create();
        $admin = User::factory()->admin()->create();
        $superAdmin = User::factory()->superAdmin()->make();
        $roles = ['dispatcher', 'delivery_driver'];

        $this->actingAs($admin);

        foreach ($roles as $role) {
            $this->from(route('user.update-role-view', $target->id))
                ->post(route('user.update-role', ['id' => $target->id, 'role' => $role]))
                ->assertRedirect(route('users'))
                ->assertSessionHas([
                    'status' => 'error', 'reason' => 'Unauthorized'
                ]);
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

    public function testSuperAdminIsUndowngradable(): void
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

    public function testAdminCannotUpgradeOrDowngradeNonExistentUser(): void
    {
        $admin = User::factory()->admin()->make();
        $user = User::factory()->create();

        $user->delete();

        $this->actingAs($admin)
            ->from(route('users'))
            ->post(route('user.update-role', ['id' => $user->id, 'role' => 'dispatcher']))
            ->assertRedirect(route('users'))
            ->assertSessionHas([
                'status' => 'error', 'reason' => 'Not Found'
            ]);
    }
}
