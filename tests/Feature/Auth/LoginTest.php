<?php

namespace Tests\Feature\Auth;

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

        $this->actingAs($user);

        $this->get(route('login'))
            ->assertRedirect(route('dashboard'));

        $this->post(route('login'))
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
            'password' => 'password'
        ];

        $this->from(route('login'))
            ->post(route('login'), $form)
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('status', 'success');

        $this->assertAuthenticated();
            
        return $user->email;
    }

    /**
     * @depends testGuestCanLoginWithValidCredentials
     */
    public function testGuestCannotLoginWithInvalidCredentials($email)
    {
        $emails = [$email, 'non-existent@foo.bar'];
        $password = 'incorrect-password';

        foreach ($emails as $email) {
            $this->from(route('login'))
                ->post(route('login'), ['email' => $email, 'password' => $password])
                ->assertRedirect(route('login'))
                ->assertSessionHas('status', 'error');
        }

        $this->assertGuest();
    }

    public function testInputsAreFlashedExceptPassword()
    {
        $form = [
            'email' => 'not-existent-email@foo.bar',
            'password' => 'incorrect-password',
            'remember' => 'on'
        ];

        $this->from(route('login'))
            ->post(route('login'), $form)
            ->assertRedirect(route('login'))
            ->assertSessionHasInput([
                'email' => 'not-existent-email@foo.bar',
                'remember' => 'on'
            ]);
    }

    public function testPendingUserCannotBeAuthenticated()
    {
        $user = User::factory()->pending()->create();

        $form = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $this->from(route('login'))
            ->post(route('login'), $form)
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'warning');

        $this->assertGuest();
    }
}