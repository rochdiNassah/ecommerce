<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\{Member, Product, Order};

final class ViewTest extends TestCase
{
    public function testHomePageViewIsRenderable(): void
    {
        $this->get(route('home'))->assertOk()->assertViewIs('home');
    }

    public function testCreateOrderViewIsRenderble(): void
    {
        $product = Product::factory()->create();
        
        $this->get(route('order.create-view', $product->id))->assertOk()->assertViewIs('order.create');
    }

    public function testRequestMyOrdersViewIsRenderable(): void
    {
        $this->get(route('order.request-my-orders-view'))->assertOk()->assertViewIs('order.request-my-orders');
    }

    public function testMyOrdersViewIsRenderable(): array
    {
        $order = Order::factory()->pending()->create();
        $email = json_decode($order->customer)->email;
        $params = ['email' => $email, 'token' => $order->token];

        $this->get(route('order.my-orders', $params))->assertOk()->assertViewIs('order.my-orders');

        return $params;
    }

    /** @depends testMyOrdersViewIsRenderable */
    public function testTrackMyOrderViewIsRenderable(array $params): void
    {
        $this->get(route('order.track-view', $params))->assertOk()->assertViewIs('order.track');
    }

    public function testLoginViewIsRenderable(): void
    {
        $this->get(route('login'))->assertOk()->assertViewIs('auth.login');
    }

    public function testForgotPasswordViewIsRenderable(): void
    {
        $this->get(route('password.request'))->assertOk()->assertViewIs('auth.forgot-password');
    }

    public function testResetPasswordViewIsRenderable(): void
    {
        $this->get(route('password.reset', 'foo'))->assertOk()->assertViewIs('auth.reset-password');
    }

    public function testJoinViewIsRendarable(): void
    {
        $this->get(route('join'))->assertOk()->assertViewIs('auth.join');
    }

    public function testDashboardViewIsRenderable(): void
    {
        $sequence = new Sequence(
            ['role' => 'admin'],
            ['role' => 'dispatcher'],
            ['role' => 'delivery_driver']
        );
        $members = Member::factory()->count(3)->state($sequence)->create();

        foreach ($members as $member) {
            $this->actingAs($member)->get('dashboard')->assertOk()->assertViewIs("{$member->role}.dashboard");
        }
    }

    public function testMembersViewIsRenderable(): void
    {
        $admin = Member::factory()->admin()->create();
        
        $this->actingAs($admin)->get(route('members'))->assertOk()->assertViewIs('admin.member.index');
    }

    public function testUpdateMemberRoleViewIsRenderable(): void
    {
        $admin = Member::factory()->admin()->create();
        $member = Member::factory()->create();
        
        $this->actingAs($admin)->get(route('member.update-role-view', $member->id))->assertOk()->assertViewIs('admin.member.update-role');
    }

    public function testProductsViewIsRenderable(): void
    {
        $admin = Member::factory()->admin()->create();

        $this->actingAs($admin)->get(route('products'))->assertOk()->assertViewIs('admin.product.index');
    }

    public function testCreateProductViewIsRenderable(): void
    {
        $admin = Member::factory()->admin()->create();

        $this->actingAs($admin)->get(route('product.create'))->assertOk()->assertViewIs('admin.product.create');
    }

    public function testDispatchOrderViewIsRenderable(): void
    {
        $dispatcher = Member::factory()->dispatcher()->create();
        $order = Order::factory()->pending()->create();

        $this->actingAs($dispatcher)->get(route('order.dispatch-view', $order->id))->assertOk()->assertViewIs('order.dispatch');
    }
}
