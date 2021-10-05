<?php

namespace Tests\Feature\Admin\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;
use App\Notifications\UserRejected;

class DeleteTest extends TestCase
{
    public function testAdminCanDeleteUser()
    {
        $admin = User::factory()->admin()->create();
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($admin);

        $sequence = new Sequence(
            ['role' => 'delivery_driver'],
            ['role' => 'dispatcher'],
            ['role' => '']
        );

        $users = User::factory()->count(3)->state($sequence)->create();

        foreach ($users as $user) {
            $this->from(route('dashboard'))
                ->get(route('user.delete', $user->id))
                ->assertRedirect(route('dashboard'))
                ->assertSessionHas('status', 'success');

            $this->assertDatabaseMissing('users', ['id' => $user->id]);
        }

        $this->actingAs($superAdmin);
        $this->get(route('user.delete', $admin->id));
        $this->assertDatabaseMissing('users', ['id' => $admin->id]);
    }

    public function testSuperAdminCannotBeDeletedExceptByThemselves()
    {
        $admin = User::factory()->admin()->make();
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($admin)
            ->from(route('dashboard'))
            ->get(route('user.delete', $superAdmin->id))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('reason', 'Unauthorized');

        $this->assertDatabaseHas('users', ['id' => $superAdmin->id]);

        $this->actingAs($superAdmin);
        $this->get(route('user.delete', $superAdmin->id));
        $this->assertDatabaseMissing('users', ['id' => $superAdmin->id]);
    }

    public function testAdminCannotDeleteNonExistentUser()
    {
        $admin = User::factory()->admin()->make();
        $user = User::factory()->create();

        $user->delete();

        $this->actingAs($admin)
            ->from(route('dashboard'))
            ->get(route('user.delete', $user->id))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('reason', 'Not Found');
    }

    public function testPendingUserIsNotifiedAfterDeletionAsThoughTheyWereRejected()
    {
        Notification::fake();

        $pending = User::factory()->pending()->create();
        $admin = User::factory()->admin()->make();

        $this->actingAs($admin)->get(route('user.delete', $pending->id));

        Notification::AssertSentTo($pending, UserRejected::class);
    }
}
