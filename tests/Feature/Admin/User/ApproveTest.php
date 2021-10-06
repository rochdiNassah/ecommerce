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

    public function testAdminCannotApproveNonPendingUser()
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

    public function testAdminCannotApproveNonExistentUser()
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

    public function testUserIsNotifiedWhenTheyApproved()
    {
        Notification::fake();

        $pending = User::factory()->pending()->create();
        $admin = User::factory()->make(['role' => 'admin']);

        $this->actingAs($admin)->get(route('user.approve', $pending->id));
        
        Notification::assertSentTo($pending, UserApproved::class);
    }
}
