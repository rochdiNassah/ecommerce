<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Member;

final class LoginTest extends TestCase
{
    public function testGuestCanLoginWithValidCredentials(): string
    {
        $user = Member::factory()->create();
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

    /** @depends testGuestCanLoginWithValidCredentials */
    public function testGuestCannotLoginWithInvalidCredentials($email): void
    {
        $emails = [$email, 'non-existent@foo.bar'];
        $password = 'incorrect-password';

        foreach ($emails as $email) {
            $this->post(route('login'), ['email' => $email, 'password' => $password])->assertSessionHas('status', 'error');
        }

        $this->assertGuest();
    }

    public function testPendingMemberCannotBeAuthenticated(): void
    {
        $user = Member::factory()->pending()->create();
        $form = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $this->post(route('login'), $form)->assertSessionHas('status', 'warning');
        $this->assertGuest();
    }

    public function testInputsAreFlashedOnFailure(): void
    {
        $form = [
            'email' => 'not-existent-email@foo.bar',
            'password' => 'incorrect-password',
            'remember' => 'on'
        ];
        
        $this->post(route('login'), $form)
            ->assertSessionHasInput([
                'email' => 'not-existent-email@foo.bar',
                'remember' => 'on'
            ]);
    }
}