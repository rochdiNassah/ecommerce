<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\Member;
use App\Notifications\JoinRequested;

final class JoinTest extends TestCase
{
    public function testValidJoinRequestCanBePlaced(): array
    {
        Notification::fake();

        $email = Str::random(10).'@foo.bar';
        $form = [
            'fullname' => 'Foo Bar',
            'email' => $email,
            'phone_number' => Str::repeat('0', 10),
            'password' => '1234',
            'password_confirmation' => '1234',
            'role' => 'dispatcher',
            'status' => 'active',
            'is_super_admin' => 1,
            'avatar_path' => 'foo'
        ];

        $this->post(route('join'), $form)->assertSessionHas('status', 'success');
        $this->assertDatabaseHas('members', [
            'email' => $email,
            'status' => 'pending',
            'role' => 'dispatcher',
            'is_super_admin' => false
        ]);

        Notification::assertSentTo(Member::where('email', $email)->get(), JoinRequested::class);

        return $form;
    }


    /** @depends testValidJoinRequestCanBePlaced */
    public function testJoinRequestValidation($form): void
    {
        $invalidRoles =  [
            'aadmin', 'admina', 'admi', 'dmin', 'ddispatcher', 'dispatcherr', 'dispat', 'cher',
            'ddelivery_driver','delivery_driverr', 'delivery_dr', 'driver', ' ', '~!@#$%^&*()_+\/'
        ];

        foreach ($invalidRoles as $invalidRole) {
            $this->post(route('join'), ['role' => $invalidRole])->assertSessionHasErrors('role');
        }

        $this->post(route('join'), ['email' => $form['email']])->assertSessionHasErrors('email');
        $this->post(route('join'), ['email' => Str::repeat('a', 256).'@foo.bar'])->assertSessionHasErrors('email');
        $this->post(route('join'), ['fullname' => 'a'])->assertSessionHasErrors('fullname');
        $this->post(route('join'), ['fullname' => Str::repeat('a', 257)])->assertSessionHasErrors('fullname');
        $this->post(route('join'), ['password' => '1234', 'password_confirmation' => '123'])->assertSessionHasErrors('password');
        $this->post(route('join'), ['password' => '123', 'password_confirmation' => '123'])->assertSessionHasErrors('password');
    }

    /** @depends testValidJoinRequestCanBePlaced */
    public function testInputsAreFlashedOnFailure($form): void
    {
        $inputs = array_flip(
            array_intersect(
                array_flip($form), ['fullname', 'email', 'phone_number', 'role']
            )
        );
        
        $this->post(route('join'), $form)->assertSessionHasInput($inputs);
    }
}
