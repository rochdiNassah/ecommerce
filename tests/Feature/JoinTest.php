<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;

class JoinTest extends TestCase
{
    public function testAuthenticatedUserCannotAccessJoinFeature()
    {
        $user = User::factory()->make();

        $this->actingAs($user)->get(route('join'))->assertRedirect(route('dashboard'));
        $this->actingAs($user)->from(route('join'))->post(route('join'))->assertRedirect(route('dashboard'));
        $this->actingAs($user)->post(route('join'))->assertRedirect(route('dashboard'));
    }

    public function testGuestCanAccessJoinFeature()
    {
        $this->get(route('join'))->assertSuccessful()->assertViewIs('auth.join');
        $this->from(route('join'))->post(route('join'))->assertRedirect(route('join'));
    }

    public function testGuestCanJoinWithValidData()
    {
        $email = Str::random(10).'@foobar.baz';

        $user = [
            'fullname' => 'Foobar',
            'email' => $email,
            'phone_number' => '0123456789',
            'password' => '1234',
            'password_confirmation' => '1234',
            'role' => ' dispatcher '
        ];

        $response = $this->from(route('join'))->post(route('join'), $user)->assertRedirect(route('login'))->assertSessionHas(['status' => 'success']);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'role' => 'dispatcher',
            'status' => 'pending',
            'avatar_path' => config('app.default_avatar_path')
        ]);
    }

    public function testGuestCannotJoinWithInvalidData()
    {
        $email = Str::random(10).'@foobar.baz';

        $response = $this->from(route('join'))->post(route('join'), [
            'fullname' => '',
            'email' => '',
            'phone_number' => '',
            'password' => '',
            'password_confirmation' => '',
            'role' => ''
        ])->assertRedirect(route('join'))->assertSessionHasErrors(['fullname', 'email', 'phone_number', 'password', 'role']);

        $response = $this->from(route('join'))->post(route('join'), [
            'fullname' => 'a',
            'email' => $email,
            'phone_number' => '0123456789',
            'password' => '1234',
            'password_confirmation' => '12345',
            'role' => 'aadmin'
        ])->assertRedirect(route('join'))->assertSessionHasErrors(['fullname', 'password', 'role']);

        $response = $this->from(route('join'))->post(route('join'), [
            'fullname' => Str::random(101),
            'email' => 'invalid-email',
            'phone_number' => '0123',
            'password' => '1234',
            'password_confirmation' => '12345',
            'role' => 'adminn'
        ])->assertRedirect(route('join'))->assertSessionHasErrors(['fullname', 'email', 'role']);

        $response = $this->from(route('join'))->post(route('join'), [
            'fullname' => 'Foobar',
            'email' => $email,
            'phone_number' => '0123456789',
            'password' => '123',
            'password_confirmation' => '123',
            'role' => 'aadminn'
        ])->assertRedirect(route('join'))->assertSessionHasErrors(['password', 'role']);

        $this->assertDatabaseMissing('users', ['email' => $email]);
    }

    public function testInputsAreFlashedExceptPassword()
    {
        $email = Str::random(10).'@foobar.baz';

        $response = $this->from(route('join'))->post(route('join'), [
            'fullname' => 'Foobar',
            'email' => $email,
            'phone_number' => '0123456789',
            'password' => '1234',
            'password_confirmation' => '12345',
            'role' => 'admin'
        ])->assertRedirect(route('join'))->assertSessionHasInput([
            'fullname' => 'Foobar',
            'email' => $email,
            'phone_number' => '0123456789',
            'role' => 'admin'
        ]);
    }
}