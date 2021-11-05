<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as Response;
use Illuminate\Contracts\Support\Responsable;
use App\Http\Responses\ViewResponses\HomeViewResponse;
use App\Http\Responses\ViewResponses\AdminDashboardViewResponse;
use App\Http\Responses\ViewResponses\MembersViewResponse;
use App\Http\Responses\ViewResponses\ProductsViewResponse;
use App\Http\Responses\ViewResponses\DispatcherDashboardViewResponse;
use App\Http\Responses\ViewResponses\DeliveryDriverDashboardViewResponse;
use App\Http\Responses\ViewResponses\MyOrdersViewResponse;
use App\Http\Responses\ViewResponses\TrackOrderViewResponse;
use App\Http\Responses\ViewResponses\CreateOrderViewResponse;
use App\Http\Responses\ViewResponses\DispatchOrderViewResponse;
use App\Http\Responses\ViewResponses\UpdateMemberRoleViewResponse;
use App\Http\Responses\ViewResponses\ResetPasswordViewResponse;
use App\Http\Responses\ViewResponses\PendingMembersViewResponse;

class ViewController extends Controller
{
    /**
     * Display all products for the customer.
     * 
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function home(): Responsable
    {
        return app(HomeViewResponse::class);
    }
    
    /**
     * Render the dashboard view depending on the member's role.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function dashboard(Request $request): Responsable
    {
        $member = $request->user();

        if ('admin' === $member->role) {
            return app(AdminDashboardViewResponse::class);
        }
        if ('dispatcher' === $member->role) {
            return app(DispatcherDashboardViewResponse::class);
        }
        if ('delivery_driver' === $member->role) {
            return app(DeliveryDriverDashboardViewResponse::class);
        }
    }

    /**
     * Render my orders view.
     * 
     * @param  string  $email
     * @param  string  $token
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function myOrders(string $email, string $token): Responsable
    {
        return app(MyOrdersViewResponse::class, [
            'email' => $email,
            'token' => $token
        ]);
    }

    /**
     * Render track order view.
     * 
     * @param  string  $token
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function trackOrder(string $token): Responsable
    {
        return app(TrackOrderViewResponse::class, ['token' => $token]);
    }

    /**
     * Display active members for admin.
     * 
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function members(): Responsable
    {
        return app(MembersViewResponse::class);
    }

    /**
     * Display pending members for admin.
     * 
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function pendingMembers()
    {
        return app(PendingMembersViewResponse::class);
    }

    /**
     * Display all products for admin.
     * 
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function products(): Responsable
    {
        return app(ProductsViewResponse::class);
    }

    /**
     * Display update member role view.
     * 
     * @param  int  $member_id
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function updateMemberRole(int $member_id): Responsable
    {
        return app(UpdateMemberRoleViewResponse::class, ['member_id' => $member_id]);
    }

    /**
     * Render create order view.
     * 
     * @param  int  $product_id
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function createOrder(int $product_id): Responsable
    {
        return app(CreateOrderViewResponse::class, ['product_id' => $product_id]);
    }

    /**
     * Render dispatch order view.
     * 
     * @param  int  $order_id
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function dispatchOrder(int $order_id): Responsable
    {
        return app(DispatchOrderViewResponse::class, ['order_id' => $order_id]);
    }

    /**
     * Render reset password view.
     * 
     * @param  string  $token
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function resetPassword(string $token): Responsable
    {
        return app(ResetPasswordViewResponse::class, ['token' => $token]);
    }
}
