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

        foreach ([$dispatcher, $deliveryDriver] as $actor) {
            $this->actingAs($actor)
                ->get(route('users'))
                ->assertRedirect()
                ->assertSessionHas([
                    'status' => 'warning',
                    'reason' => 'Unauthorized'
                ]);
        }

        $this->actingAs($admin)
            ->get(route('users'))
            ->assertSuccessful()
            ->assertViewIs('admin.users');
        
        Auth::logout();

        $this->from(route('dashboard'))
            ->get(route('users'))
            ->assertRedirect(route('login'));
    }
}
