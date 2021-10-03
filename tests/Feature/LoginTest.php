<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Tests\Feature\JoinTest;

class LoginTest extends TestCase
{
    public function testAuthenticatedUserCannotAccessLoginFeature()
    {
        $user = User::factory()->make();

        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect(route('dashboard'));

        $this->actingAs($user)
            ->from(route('login'))
            ->post(route('login'))
            ->assertRedirect(route('dashboard'));
            
        $this->actingAs($user)
            ->post(route('login'))
            ->assertRedirect(route('dashboard'));
    }

    public function testGuestCanAccessLoginFeature()
    {
        $this->get(route('login'))
            ->assertSuccessful()
            ->assertViewIs('auth.login');

        $this->from(route('login'))
            ->post(route('login'))
            ->assertRedirect(route('login'));
    }

    public function testGuestCanLoginWithValidCredentials()
    {
        $user = User::factory()->create();

        $form = [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->from(route('login'))
            ->post(route('login'), $form)
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('message', 'Logged In successfully.');

        $this->assertAuthenticated();
            
        return $user->email;
    }

    /**
     * @depends testGuestCanLoginWithValidCredentials
     */
    public function testGuestCannotLoginWithInvalidCredentials($email)
    {
        $form = [
            'email' => 'not-existent-email@foo.bar',
            'password' => 'incorrect-password',
        ];

        $response = $this->from(route('login'))
            ->post(route('login'), $form)
            ->assertRedirect(route('login'))
            ->assertSessionHas('message', 'The provided credentials do not match our records.');

        $form['email'] = $email;

        $response = $this->from(route('login'))
            ->post(route('login'), $form)
            ->assertRedirect(route('login'))
            ->assertSessionHas('message', 'The provided credentials do not match our records.');

        $this->assertGuest();
    }

    public function testInputsAreFlashedExceptPassword()
    {
        $form = [
            'email' => 'not-existent-email@foo.bar',
            'password' => 'incorrect-password',
            'remember' => 'on'
        ];

        $response = $this->from(route('login'))
            ->post(route('login'), $form)
            ->assertRedirect(route('login'))
            ->assertSessionHasInput([
                'email' => 'not-existent-email@foo.bar',
                'remember' => 'on'
            ]);
    }

    public function testPendingUserCannotBeAuthenticated()
    {
        $user = User::factory()->create(['status' => 'pending']);

        $form = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $this->from(route('login'))
            ->post(route('login'), $form)
            ->assertRedirect(route('login'))
            ->assertSessionHas('message', 'Your account is not yet approved. We will notify you once we do.');

        $this->assertGuest();
    }
}
