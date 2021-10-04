<?php

namespace Tests\Feature\Admin\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;
use App\Notifications\UserRejected;

class DeleteTest extends TestCase
{
    public function testAdminCanDeleteUser()
    {
        $dispatcher = User::factory()->create(['role' => 'dispatcher']);
        $delivery_driver = User::factory()->create(['role' => 'delivery_driver']);
        $admin = User::factory()->create(['role' => 'admin']);

        foreach ([$dispatcher, $delivery_driver] as $user) {
            $this->actingAs($admin)
                ->from(route('dashboard'))
                ->get(route('user.delete', $user->id))
                ->assertRedirect(route('dashboard'))
                ->assertSessionHas('status', 'success');

            $this->assertDatabaseMissing('users', ['id' => $user->id]);
        }
    }

    public function testSuperAdminCannotBeDeletedExceptByThemselves()
    {
        $admin = User::factory()->make(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('dashboard'))
            ->get(route('user.delete', 1))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas([
                'status' => 'error',
                'reason' => 'Unauthorized'
            ]);

        $this->assertDatabaseHas('users', ['id' => 1]);
    }

    public function testAdminCannotDeleteNonExistentUser()
    {
        $admin = User::factory()->make(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('dashboard'))
            ->get(route('user.delete', -1))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas([
                'status' => 'error',
                'reason' => 'Not Found'
            ]);
    }

    public function testPendingUserIsNotifiedAfterDeletionAsThoughTheyWereRejected()
    {
        Notification::fake();

        $pendingUser = User::factory()->create(['status' => 'pending']);
        $admin = User::factory()->make(['role' => 'admin']);

        $this->actingAs($admin)->get(route('user.delete', $pendingUser->id));

        Notification::AssertSentTo($pendingUser, UserRejected::class);
    }
}
