<?php declare(strict_types=1);

namespace Tests;

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
