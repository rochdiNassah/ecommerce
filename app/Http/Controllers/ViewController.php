<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as Response;
use App\Models\{User, Product, Order};
use App\Http\Responses\ViewResponses\AdminDashboardViewResponse;
use App\Http\Responses\ViewResponses\DispatcherDashboardViewResponse;
use App\Http\Responses\ViewResponses\DeliveryDriverDashboardViewResponse;


class ViewController extends Controller
{
    /**
     * Render the dashboard view depending on the user's role.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request): AdminDashboardViewResponse
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
     * @return \Illuminate\View\View
     */
    public function myOrders(string $email, string $token): Response
    {
        Order::where('customer->email', $email)->where('token', $token)->firstOrFail();

        $filter = request('filter') ?? null;
        $orders = Order::where('customer->email', $email)
            ->orderBy('created_at', 'desc')
            ->where(function ($query) use ($filter) {
                if ('canceled' === $filter) {
                    $query->where('status', $filter);
                } elseif ('rejected' === $filter) {
                    $query->where('status', $filter);
                } elseif (null === $filter) {
                    $query->where('status', '!=', 'canceled')->where('status', '!=', 'rejected');
                } else {
                    $query->where('status', $filter);
                }
            })
            ->paginate(8);

        return view('order.my-orders', ['orders' => $orders, 'query' => request('filter')]);
    }

    /**
     * Render request to view my orders view.
     * 
     * @return \Illuminate\View\View
     */
    public function requestMyOrders(): Response
    {
        return view('order.request-my-orders');
    }
    
    /**
     * Render track order view.
     * 
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function trackOrder(string $token): Response
    {
        $order = Order::where('token', $token)->firstOrFail();

        return view('order.track', ['order' => $order]);
    }

    /**
     * Display all members for admin.
     * 
     * @return \Illuminate\View\View
     */
    public function users(): Response
    {
        return View::make('admin.user.index', [
            'users' => User::orderBy('status')->get()
        ]);
    }

    /**
     * Display the update role screen.
     * 
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function updateMemberRole(int $id): Response
    {
        return View::make('admin.user.update-role', ['user' => User::findOrFail($id)]);
    }

    /**
     * Display all products for admin.
     * 
     * @return \Illuminate\View\View
     */
    public static function products(): Response
    {
        return View::make('admin.product.index', ['products' => Product::all()]);
    }

    /**
     * Display all products for customer.
     * 
     * @return \Illuminate\View\View
     */
    public function home(): Response
    {
        $search = request('search') ?? null;
        $products = Product::where(function ($query) use ($search) {
            !$search ?: $query->where('name', 'like', '%'.$search.'%');
        })->paginate(12);
        $data = ['products' => $products, 'query' => $search];

        return view('home', $data);
    }

    /**
     * Display create order view.
     * 
     * @param  int  $productId
     * @return \Illuminate\View\View
     */
    public function createOrder($productId): Response
    {
        return View::make('order.create', ['product' => Product::findOrFail($productId)]);
    }

    /**
     * Display dispatch order view.
     * 
     * @param  int  $orderId
     * @return \Illuminate\View\View
     */
    public function dispatchOrder($orderId): Response
    {
        return View::make('order.dispatch', [
            'order' => Order::findOrFail($orderId),
            'delivery_drivers' => User::where('role', 'delivery_driver')->where('status', 'active')->get()
        ]);
    }
}
