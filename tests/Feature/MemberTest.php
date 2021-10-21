<?php declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;

final class MemberTest extends TestCase
{
    private $member;

    public function setUp(): void
    {
        parent::setUp();

        $this->member = User::factory()->create();
    }

    /**
     * Assert that a member can login.
     * 
     * @return void
     */
    public function testCanLogin(): void
    {
        $form = [
            'email' => $this->member->email,
            'password' => 'password'
        ];

        $this->post(route('login'), $form);

        $this->assertAuthenticated();
    }

    /**
     * Assert that a member can logout.
     *  
     * @return void
     */
    public function testCanLogout(): void
    {
        $this->actingAs($this->member);

        $this->get(route('logout'));

        $this->assertGuest();
    }

    /**
     * Assert that a member can place a join request.
     * 
     * @return void
     */
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

        $response = $this->post(route('user.update-role'), $form);

        $this->assertDatabaseHas('users', $form);
    }

    /**
     * Assert that a member can be deleted.
     *
     * @return void
     */
    public function testIsDeletable(): void
    {
        $this->actAsAdmin();

        $this->get(route('user.delete', $this->member->id));

        $this->assertDatabaseMissing('users', ['id' => $this->member->id]);
    }

    /**
     * Act as an admin.
     * 
     * @return void
     */
    private function actAsAdmin(): void
    {
        $admin = User::factory()->admin()->make();

        $this->actingAs($admin);
    }
}
