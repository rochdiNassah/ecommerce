<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\{User, Product, Order};

final class ViewTest extends TestCase
{
    /** @return void */
    public function testHomePageViewIsRenderable(): void
    {
        $this->get(route('home'))->assertOk()->assertViewIs('home');
    }

    /** @return void */
    public function testCreateOrderViewIsRenderble(): void
    {
        $product = Product::factory()->create();
        
        $this->get(route('order.create-view', $product->id))->assertOk()->assertViewIs('order.create');
    }

    /** @return void */
    public function testRequestMyOrdersViewIsRenderable(): void
    {
        // $this->markTestSkipped('TODO');

        $this->get(route('order.request-my-orders-view'))->assertOk()->assertViewIs('order.request-my-orders');
    }

    /** @return array */
    public function testMyOrdersViewIsRenderable(): array
    {
        // $this->markTestSkipped('TODO');

        $order = Order::factory()->pending()->create();
        $email = json_decode($order->customer)->email;
        $params = ['email' => $email, 'token' => $order->token];

        $this->get(route('order.my-orders', $params))->assertOk()->assertViewIs('order.my-orders');

        return $params;
    }

    /**
     * @depends testMyOrdersViewIsRenderable
     * 
     * @param  array  $params
     * @return void
     */
    public function testTrackMyOrderViewIsRenderable(array $params): void
    {
        // $this->markTestSkipped('TODO');

        $this->get(route('order.track-view', $params))->assertOk()->assertViewIs('order.track');
    }

    /** @return void */
    public function testLoginViewIsRenderable(): void
    {
        $this->get(route('login'))->assertOk()->assertViewIs('auth.login');
    }

    /** @return void */
    public function testJoinViewIsRendarable(): void
    {
        $this->get(route('join'))->assertOk()->assertViewIs('auth.join');
    }

    /** @return void */
    public function testDashboardViewIsRenderable(): void
    {
        $sequence = new Sequence(
            ['role' => 'admin'],
            ['role' => 'dispatcher'],
            ['role' => 'delivery_driver']
        );
        $members = User::factory()->count(3)->state($sequence)->create();

        foreach ($members as $member) {
            $this->actingAs($member)->get('dashboard')->assertOk()->assertViewIs("{$member->role}.dashboard");
        }
    }

    /** @return void */
    public function testUsersViewIsRenderable(): void
    {
        $admin = User::factory()->admin()->create();
        
        $this->actingAs($admin)->get(route('users'))->assertOk()->assertViewIs('admin.user.index');
    }

    /** @return void */
    public function testUpdateMemberRoleViewIsRenderable(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create();
        
        $this->actingAs($admin)->get(route('user.update-role-view', $member->id))->assertOk()->assertViewIs('admin.user.update-role');
    }

    /** @return void */
    public function testProductsViewIsRenderable(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('products'))->assertOk()->assertViewIs('admin.product.index');
    }

    /** @return void */
    public function testCreateProductViewIsRenderable(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('product.create'))->assertOk()->assertViewIs('admin.product.create');
    }

    /** @return void */
    public function testDispatchOrderViewIsRenderable(): void
    {
        $dispatcher = User::factory()->dispatcher()->create();
        $order = Order::factory()->pending()->create();

        $this->actingAs($dispatcher)->get(route('order.dispatch-view', $order->id))->assertOk()->assertViewIs('order.dispatch');
    }
}
