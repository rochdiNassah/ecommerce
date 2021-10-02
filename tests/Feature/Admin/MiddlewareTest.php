<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class MiddlewareTest extends TestCase
{
    public function testAdminOnlyCanAccessAdministrativeFeatures()
    {
        $admin = User::factory()->make(['role' => 'admin']);
        $dispatcher = User::factory()->make(['role' => 'dispatcher']);
        $deliveryDriver = User::factory()->make(['role' => 'delivery_driver']);
        $foo = User::factory()->make(['role' => 'foo']);
        $bar = User::factory()->make(['role' => '']);
        $baz = User::factory()->make(['role' => base64_encode(random_bytes(10000000))]);

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

        $this->actingAs($foo)
            ->get(route('users'))
            ->assertRedirect()
            ->assertSessionHas([
                'status' => 'warning',
                'reason' => 'Unauthorized'
            ]);

        $this->actingAs($bar)
            ->get(route('users'))
            ->assertRedirect()
            ->assertSessionHas([
                'status' => 'warning',
                'reason' => 'Unauthorized'
            ]);

        $this->actingAs($baz)
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

    public function testAdminOnlyCanApproveUser()
    {
        $pendingUser = User::factory()->create(['status' => 'pending']);

        $admin = User::factory()->make(['role' => 'admin']);
        $dispatcher = User::factory()->make(['role' => 'dispatcher']);
        $deliveryDriver = User::factory()->make(['role' => 'delivery_driver']);
        $foo = User::factory()->make(['role' => 'foo']);
        $bar = User::factory()->make(['role' => '']);
        $baz = User::factory()->make(['role' => base64_encode(random_bytes(10000000))]);

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

        $this->actingAs($foo)
            ->get(route('user.approve', $pendingUser->id))
            ->assertRedirect()
            ->assertSessionHas([
                'status' => 'warning',
                'reason' => 'Unauthorized'
            ]);

        $this->actingAs($bar)
            ->get(route('user.approve', $pendingUser->id))
            ->assertRedirect()
            ->assertSessionHas([
                'status' => 'warning',
                'reason' => 'Unauthorized'
            ]);

        $this->actingAs($baz)
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
