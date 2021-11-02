<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;
use App\Notifications\UserApproved;

final class ApproveMemberTest extends TestCase
{
    public function testAdminCanApprovePendingUser(): void
    {
        $pending = User::factory()->pending()->create();
        $admin = User::factory()->admin()->make();
        
        $this->actingAs($admin)
            ->from(route('dashboard'))
            ->get(route('user.approve', $pending->id))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('status', 'success');
        $this->assertDatabaseHas('users', [
            'email' => $pending->email,
            'status' => 'active'
        ]);
    }

    public function testAdminCannotApproveNonPendingUser(): void
    {
        $active = User::factory()->create();
        $admin = User::factory()->admin()->make();

        $this->actingAs($admin)
            ->get(route('user.approve', $active->id))
            ->assertSessionHas([
                'status' => 'warning',
                'reason' => 'Already'
            ]);
    }

    public function testAdminCannotApproveNonExistentUser(): void
    {
        $admin = User::factory()->admin()->make();
        $user = User::factory()->create();

        $user->delete();

        $this->actingAs($admin);
        $this->get(route('user.approve', $user->id))
            ->assertSessionHas([
                'status' => 'error',
                'reason' => 'Not Found'
            ]);
    }

    public function testUserIsNotifiedWhenTheyApproved(): void
    {
        Notification::fake();

        $pending = User::factory()->pending()->create();
        $admin = User::factory()->make(['role' => 'admin']);

        $this->actingAs($admin)->get(route('user.approve', $pending->id));
        
        Notification::assertSentTo($pending, UserApproved::class);
    }
}
