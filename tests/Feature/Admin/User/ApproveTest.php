<?php

namespace Tests\Feature\Admin\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;
use App\Notifications\UserApproved;

class ApproveTest extends TestCase
{
    public function testAdminCanApprovePendingUser()
    {
        $pendingUser = User::factory()->create(['status' => 'pending']);
        $activeUser = User::factory()->create();

        $admin = User::factory()->make(['role' => 'admin']);
        
        $this->actingAs($admin)
            ->from(route('dashboard'))
            ->get(route('user.approve', $pendingUser->id))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas(['status' => 'success']);
        
        $this->assertDatabaseHas('users', [
            'email' => $pendingUser->email,
            'status' => 'active'
        ]);
    }

    public function testAdminCannotApproveNonPendingUser()
    {
        $activeUser = User::factory()->create();
        $admin = User::factory()->make(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('user.approve', $activeUser->id))
            ->assertSessionHas([
                'status' => 'warning',
                'reason' => 'Already'
            ]);
    }

    public function testAdminCannotApproveNonExistentUser()
    {
        $admin = User::factory()->make(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('user.approve', '-1'))
            ->assertSessionHas([
                'status' => 'error',
                'reason' => 'Not Found'
            ]);
    }

    public function testUserIsNotifiedWhenTheyApproved()
    {
        Notification::fake();

        $pendingUser = User::factory()->create(['status' => 'pending']);
        $admin = User::factory()->make(['role' => 'admin']);

        $this->actingAs($admin)->get(route('user.approve', $pendingUser->id));
        
        Notification::assertSentTo($pendingUser, UserApproved::class);
    }
}
