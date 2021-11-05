<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\Member;
use App\Notifications\MemberRejected;

final class DeleteMemberTest extends TestCase
{
    public function testAdminCanDeleteMember(): void
    {
        $admin = Member::factory()->admin()->create();
        $super_admin = Member::factory()->superAdmin()->create();
        $sequence = new Sequence(
            ['role' => 'delivery_driver'],
            ['role' => 'dispatcher'],
            ['role' => '']
        );
        $members = Member::factory()->count(3)->state($sequence)->create();

        $this->actingAs($admin);
        
        foreach ($members as $member) {
            $this->from(route('dashboard'))
                ->get(route('member.delete', $member->id))
                ->assertRedirect(route('dashboard'))
                ->assertSessionHas('status', 'success');
            $this->assertDatabaseMissing('members', ['id' => $member->id]);
        }

        $this->actingAs($super_admin);
        $this->get(route('member.delete', $admin->id));
        $this->assertDatabaseMissing('members', ['id' => $admin->id]);
    }

    public function testSuperAdminIsUndeletable(): void
    {
        $admin = Member::factory()->admin()->make();
        $super_admin = Member::factory()->superAdmin()->create();

        $this->actingAs($admin)
            ->get(route('member.delete', $super_admin->id))
            ->assertSessionHas([
                'status' => 'error', 'reason' => 'Unauthorized'
            ]);
        $this->actingAs($super_admin)
            ->get(route('member.delete', $super_admin->id))
            ->assertSessionHas([
                'status' => 'error', 'reason' => 'Unauthorized'
            ]);
        $this->assertDatabaseHas('members', ['id' => $super_admin->id]);
    }

    public function testAdminCannotDeleteNonExistentMember(): void
    {
        $admin = Member::factory()->admin()->make();
        $member = Member::factory()->create();

        $member->delete();

        $this->actingAs($admin)
            ->from(route('dashboard'))
            ->get(route('member.delete', $member->id))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas([
                'status' => 'error', 'reason' => 'Not Found'
            ]);
    }

    public function testPendingMemberIsNotifiedAfterDeletionAsThoughTheyWereRejected(): void
    {
        Notification::fake();

        $pending = Member::factory()->pending()->create();
        $admin = Member::factory()->admin()->make();

        $this->actingAs($admin)->get(route('member.delete', $pending->id));

        Notification::AssertSentTo($pending, MemberRejected::class);
    }
}
