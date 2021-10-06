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
    public function formProvider()
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

        $this->get(route('join'))
            ->assertRedirect(route('dashboard'));

        $this->post(route('join'))
            ->assertRedirect(route('dashboard'));
    }

    public function testGuestCanAccessJoinFeature()
    {
        $this->get(route('join'))
            ->assertSuccessful()
            ->assertViewIs('auth.join');

        $this->from(route('join'))
            ->post(route('join'))
            ->assertRedirect(route('join'));
    }

    /**
     * @dataProvider formProvider
     */
    public function testGuestCanPlaceValidJoinRequest($form)
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

        return $form['email'];
    }

    /**
     * @depends testGuestCanPlaceValidJoinRequest
     */
    public function testGuestCannotPlaceInvalidJoinRequest($email)
    {
        $roles = [
            'admina', 'aadmin', 'admi', 'admin%00', '#admin#', 'aadminb',
            'disptachera', 'aadisptacher', 'dispat', 'adisptacher%00', '#disptacher#', 'adisptacherb',
            'delivery_drivera', 'adelivery_driver', 'delivery_', 'delivery_driver%00', '#delivery_driver#', 'adelivery_driverb',
            'ADMIN', 'DISPACTHER', 'DELIVERY_DRIVER', ' ', '', '~!@#$%^&*()_+\/'
        ];

        foreach ($roles as $role) {
            $this->post(route('join'), ['role' => $role])->assertSessionHasErrors('role');
        }

        $this->post(route('join'), ['email' => $email])->assertSessionHasErrors('email');
        $this->post(route('join'), ['email' => Str::repeat('a', 256).'@foo.bar'])->assertSessionHasErrors('email');
        $this->post(route('join'), ['fullname' => 'a'])->assertSessionHasErrors('fullname');
        $this->post(route('join'), ['fullname' => Str::repeat('a', 101)])->assertSessionHasErrors('fullname');
        $this->post(route('join'), ['password' => '1234', 'password_confirmation' => '123'])->assertSessionHasErrors('password');
        $this->post(route('join'), ['password' => '123', 'password_confirmation' => '123'])->assertSessionHasErrors('password');

        $form = [
            'fullname' => '',
            'email' => '',
            'phone_number' => '',
            'password' => '',
            'role' => '',
            'password_confirmation' => ''
        ];

        $this->post(route('join'), $form)->assertSessionHasErrors(array_keys(array_slice($form, 0, -1)));
    }

    /**
     * @dataProvider formProvider
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
     * @dataProvider formProvider
     */
    public function testUserIsNotifiedWhenTheyRequestToJoin($form)
    {
        Notification::fake();

        $this->from(route('join'))->post(route('join'), $form);

        Notification::assertSentTo(User::where('email', $form['email'])->get(), JoinRequested::class);
    }
}