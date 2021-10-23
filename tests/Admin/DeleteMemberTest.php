<?php declare(strict_types=1);

namespace Tests\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;
use App\Notifications\UserRejected;

final class DeleteTest extends TestCase
{
    /** @return void */
    public function testAdminCanDeleteUser(): void
    {
        $admin = User::factory()->admin()->create();
        $superAdmin = User::factory()->superAdmin()->create();
        $sequence = new Sequence(
            ['role' => 'delivery_driver'],
            ['role' => 'dispatcher'],
            ['role' => '']
        );
        $users = User::factory()->count(3)->state($sequence)->create();

        $this->actingAs($admin);
        
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

    /** @return void */
    public function testCannotDeleteSuperAdmin(): void
    {
        $admin = User::factory()->admin()->make();
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($admin)
            ->get(route('user.delete', $superAdmin->id))
            ->assertSessionHas([
                'status' => 'error', 'reason' => 'Unauthorized'
            ]);
        $this->actingAs($superAdmin)
            ->get(route('user.delete', $superAdmin->id))
            ->assertSessionHas([
                'status' => 'error', 'reason' => 'Unauthorized'
            ]);
        $this->assertDatabaseHas('users', ['id' => $superAdmin->id]);
    }

    /** @return void */
    public function testAdminCannotDeleteNonExistentUser(): void
    {
        $admin = User::factory()->admin()->make();
        $user = User::factory()->create();

        $user->delete();

        $this->actingAs($admin)
            ->from(route('dashboard'))
            ->get(route('user.delete', $user->id))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas([
                'status' => 'error', 'reason' => 'Not Found'
            ]);
    }

    /** @return void */
    public function testPendingUserIsNotifiedAfterDeletionAsThoughTheyWereRejected(): void
    {
        Notification::fake();

        $pending = User::factory()->pending()->create();
        $admin = User::factory()->admin()->make();

        $this->actingAs($admin)->get(route('user.delete', $pending->id));

        Notification::AssertSentTo($pending, UserRejected::class);
    }
}
