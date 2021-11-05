<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\Member;
use App\Notifications\MemberApproved;

final class ApproveMemberTest extends TestCase
{
    public function testAdminCanApprovePendingMember(): void
    {
        $pending = Member::factory()->pending()->create();
        $admin = Member::factory()->admin()->make();
        
        $this->actingAs($admin)
            ->from(route('dashboard'))
            ->get(route('member.approve', $pending->id))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('status', 'success');
        $this->assertDatabaseHas('members', [
            'email' => $pending->email,
            'status' => 'active'
        ]);
    }

    public function testAdminCannotApproveNonPendingMember(): void
    {
        $active = Member::factory()->create();
        $admin = Member::factory()->admin()->make();

        $this->actingAs($admin)
            ->get(route('member.approve', $active->id))
            ->assertSessionHas([
                'status' => 'warning',
                'reason' => 'Already'
            ]);
    }

    public function testAdminCannotApproveNonExistentMember(): void
    {
        $admin = Member::factory()->admin()->make();
        $member = Member::factory()->create();

        $member->delete();

        $this->actingAs($admin);
        $this->get(route('member.approve', $member->id))
            ->assertSessionHas([
                'status' => 'error',
                'reason' => 'Not Found'
            ]);
    }

    public function testMemberIsNotifiedWhenTheyAreApproved(): void
    {
        Notification::fake();

        $pending = Member::factory()->pending()->create();
        $admin = Member::factory()->make(['role' => 'admin']);

        $this->actingAs($admin)->get(route('member.approve', $pending->id));
        
        Notification::assertSentTo($pending, MemberApproved::class);
    }
}
