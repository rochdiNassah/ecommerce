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
            ->assertSessionHas(['status' => 'success', 'message' => __('user.approved')]);
        
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
                'message' => __('user.active'),
                'reason' => 'Already'
            ]);
    }

    public function testAdminCannotApproveNonExistentUser()
    {
        $admin = User::factory()->admin()->make();

        $this->actingAs($admin);

        $this->get(route('user.approve', '-1'))->assertSessionHas(['reason' => 'Not Found', 'message' => __('user.missing')]);
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
