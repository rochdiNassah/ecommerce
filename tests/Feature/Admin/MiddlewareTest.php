<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class MiddlewareTest extends TestCase
{
    public function testOnlyAdminCanAccessAdministrativeRoutes()
    {
        $admin = User::factory()->make(['role' => 'admin']);
        $dispatcher = User::factory()->make(['role' => 'dispatcher']);
        $deliveryDriver = User::factory()->make(['role' => 'delivery_driver']);

        $this->actingAs($dispatcher)
            ->get(route('users'))
            ->assertRedirect()
            ->assertSessionHas([
                'status' => 'warning',
                'reason' => 'Unauthorized'
            ]);

        $this->actingAs($deliveryDriver)
            ->get(route('users'))
            ->assertRedirect()
            ->assertSessionHas([
                'status' => 'warning',
                'reason' => 'Unauthorized'
            ]);

        $this->actingAs($admin)
            ->get(route('users'))
            ->assertSuccessful()
            ->assertViewIs('admin.users');
    }

    public function testOnlyAdminCanApproveUser()
    {
        $pendingUser = User::factory()->create(['status' => 'pending']);

        $admin = User::factory()->make(['role' => 'admin']);
        $dispatcher = User::factory()->make(['role' => 'dispatcher']);
        $deliveryDriver = User::factory()->make(['role' => 'delivery_driver']);

        $this->actingAs($dispatcher)
            ->get(route('user.approve', $pendingUser->id))
            ->assertRedirect()
            ->assertSessionHas([
                'status' => 'warning',
                'reason' => 'Unauthorized'
            ]);

        $this->actingAs($deliveryDriver)
            ->get(route('user.approve', $pendingUser->id))
            ->assertRedirect()
            ->assertSessionHas([
                'status' => 'warning',
                'reason' => 'Unauthorized'
            ]);

        $this->actingAs($admin)
            ->from(route('dashboard'))
            ->get(route('user.approve', $pendingUser->id))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas(['status' => 'success']);

        Auth::logout();

        $this->from(route('dashboard'))
            ->get(route('user.approve', $pendingUser->id))
            ->assertRedirect(route('login'));
    }
}
