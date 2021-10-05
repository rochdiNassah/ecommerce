<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;
use App\Notifications\JoinRequested;

class JoinTest extends TestCase
{
    public function provider()
    {
        return [[[
            'fullname' => 'Foo Bar',
            'email' => Str::random(10).'@foo.bar',
            'phone_number' => Str::repeat(0, 10),
            'password' => '1234',
            'password_confirmation' => '1234',
            'role' => 'dispatcher',
            'status' => 'active',
            'is_super_admin' => 1,
            'avatar_path' => 'foo'
        ]]];
    }

    public function testAuthenticatedUserCannotAccessJoinFeature()
    {
        $user = User::factory()->make();

        $this->actingAs($user);

        $this->get(route('join'))->assertRedirect(route('dashboard'));
        $this->post(route('join'))->assertRedirect(route('dashboard'));
    }

    public function testGuestCanAccessJoinFeature()
    {
        $this->get(route('join'))->assertSuccessful()->assertViewIs('auth.join');
        $this->from(route('join'))->post(route('join'))->assertRedirect(route('join'));
    }

    /**
     * @dataProvider provider
     */
    public function testGuestCanJoinWithValidData($form)
    {
        $this->from(route('join'))
            ->post(route('join'), $form)
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'success');
        
        $form = array_flip(
            array_intersect(
                array_flip($form), ['fullname', 'email', 'phone_number', 'role']
            )
        );

        $user = [
            'status' => 'pending',
            'is_super_admin' => false,
            'avatar_path' => config('app.default_avatar_path')
        ];

        $this->assertDatabaseHas('users', array_merge($form, $user));
    }

    public function testJoinCannotBeRequestedWithoutEmptyData()
    {
        $this->from(route('join'))
            ->post(route('join'), [
                'fullname' => '',
                'email' => '',
                'phone_number' => '',
                'password' => '',
                'password_confirmation' => '',
                'role' => ''
            ])
            ->assertRedirect(route('join'))
            ->assertSessionHasErrors(['fullname', 'email', 'phone_number', 'password', 'role']);

        $this->assertDatabaseMissing('users', ['email' => '']);
    }

    /**
     * @dataProvider provider
     */
    public function testInputsAreFlashedExceptPassword($form)
    {
        $form['email'] = 'invalid-email';

        $inputs = array_flip(
            array_intersect(
                array_flip($form), ['fullname', 'email', 'phone_number', 'role']
            )
        );
        
        $this->from(route('join'))
            ->post(route('join'), $form)
            ->assertRedirect(route('join'))
            ->assertSessionHasInput($inputs);
    }

    /**
     * @dataProvider provider
     */
    public function testUserIsNotifiedWhenTheyRequestToJoin($form)
    {
        Notification::fake();

        $this->from(route('join'))->post(route('join'), $form);

        Notification::assertSentTo(User::where('email', $form['email'])->get(), JoinRequested::class);
    }
}