<?php declare(strict_types=1);

namespace Tests\Member;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;

final class MemberTest extends TestCase
{
    /** @var \App\Models\User */
    private $member;

    /** @return void */
    public function setUp(): void
    {
        parent::setUp();

        $this->member = User::factory()->create();
    }

    /** @return void */
    public function testCanLogin(): void
    {
        $form = [
            'email' => $this->member->email,
            'password' => 'password'
        ];

        $this->post(route('login'), $form);
        $this->assertAuthenticated();
    }

    /** @return void */
    public function testCanLogout(): void
    {
        $this->actingAs($this->member);
        $this->get(route('logout'));
        $this->assertGuest();
    }

    /** @return void */
    public function testCanRequestJoin(): void
    {
        $form = [
            'fullname' => Str::random(10),
            'email' => Str::random(10) . '@foo.bar',
            'phone_number' => '0000000000',
            'role' => 'dispatcher',
            'password' => '1234',
            'password_confirmation' => '1234'
        ];

        $this->post(route('join'), $form);
        $this->assertDatabaseHas('users', array_slice($form, 0, -2));
    }

    /**
     * Assert that a pending member can be approved.
     * 
     * @return void
     */
    public function testIsApprovable(): void
    {
        $this->actAsAdmin();
        $this->get(route('user.approve', $this->member->id));
        $this->assertDatabaseHas('users', [
            'id' => $this->member->id,
            'status' => 'active'
        ]);
    }

    /**
     * Assert that a member can be upgraded.
     * 
     * @return \App\Models\User
     */
    public function testIsUpgradable(): User
    {
        $this->actAsAdmin();

        $form = [
            'id' => $this->member->id,
            'role' => 'dispatcher'
        ];

        $this->post(route('user.update-role'), $form);
        $this->assertDatabaseHas('users', $form);

        return $this->member;
    }

    /**
     * Assert that a member can be downgraded.
     * 
     * @depends testIsUpgradable
     * 
     * @param  \App\Models\User  $member
     * @return void
     */
    public function testIsDowngradable($member): void
    {
        $this->actAsAdmin();

        $form = [
            'id' => $member->id,
            'role' => 'delivery_driver'
        ];

        $this->post(route('user.update-role'), $form);
        $this->assertDatabaseHas('users', $form);
    }

    /** @return void */
    public function testIsDeletable(): void
    {
        $this->actAsAdmin();
        $this->get(route('user.delete', $this->member->id));
        $this->assertDatabaseMissing('users', ['id' => $this->member->id]);
    }

    /** @return void */
    private function actAsAdmin(): void
    {
        $this->actingAs(User::factory()->admin()->make());
    }
}
