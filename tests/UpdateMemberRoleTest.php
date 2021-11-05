<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Member;

final class UpdateMemberRoleTest extends TestCase
{
    public function testAdminCanUpgradeAndDowngradeMember(): void
    {
        $target = Member::factory()->create();
        $admin = Member::factory()->admin()->make();
        $roles = ['dispatcher', 'delivery_driver'];

        $this->actingAs($admin);

        foreach ($roles as $role) {
            $this->from(route('member.update-role-view', $target->id))
                ->post(route('member.update-role', ['id' => $target->id, 'role' => $role]))
                ->assertRedirect(route('members'))
                ->assertSessionHas('status', 'success');
            $this->assertDatabaseHas('members', [
                'id' => $target->id,
                'role' => $role
            ]);       
        }
    }

    public function testAdminCannotBeDowngradedExceptByTheSuperAdmin(): void
    {
        $target = Member::factory()->admin()->create();
        $admin = Member::factory()->admin()->create();
        $super_admin = Member::factory()->superAdmin()->make();
        $roles = ['dispatcher', 'delivery_driver'];

        $this->actingAs($admin);

        foreach ($roles as $role) {
            $this->from(route('member.update-role-view', $target->id))
                ->post(route('member.update-role', ['id' => $target->id, 'role' => $role]))
                ->assertRedirect(route('members'))
                ->assertSessionHas([
                    'status' => 'error', 'reason' => 'Unauthorized'
                ]);
            $this->assertDatabaseHas('members', [
                'id' => $target->id,
                'role' => 'admin'
            ]);       
        }

        $data = ['id' => $target->id, 'role' => 'dispatcher'];

        $this->actingAs($super_admin);
        $this->post(route('member.update-role', $data));
        $this->assertDatabaseHas('members', $data);
    }

    public function testSuperAdminIsUndowngradable(): void
    {
        $admin = Member::factory()->admin()->create();
        $super_admin = Member::factory()->superAdmin()->make();
        $data = ['id' => $super_admin->id, 'role' => 'dispatcher'];
        
        $this->actingAs($admin);
        $this->post(route('member.update-role', $data));
        $this->actingAs($super_admin);
        $this->post(route('member.update-role', $data));
        $this->assertDatabaseMissing('members', $data);
    }

    public function testAdminCannotUpgradeOrDowngradeNonExistentMember(): void
    {
        $admin = Member::factory()->admin()->make();
        $member = Member::factory()->create();

        $member->delete();

        $this->actingAs($admin)
            ->from(route('members'))
            ->post(route('member.update-role', ['id' => $member->id, 'role' => 'dispatcher']))
            ->assertRedirect(route('members'))
            ->assertSessionHas([
                'status' => 'error', 'reason' => 'Not Found'
            ]);
    }
}
