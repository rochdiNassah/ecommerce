<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as Response;
use App\Models\{User, Product, Order};

class ViewController extends Controller
{
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
     * Render track order status view.
     * 
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function trackOrderView(string $token): Response
    {
        $order = Order::where('token', $token)->firstOrFail();

        return view('order.track', ['order' => $order]);
    }

    /**
     * Render the dashboard view depending on the user's role.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request): Response
    {
        $member = $request->user();

        if ('admin' === $member->role) {
            $view = 'admin.dashboard';
            $data = [
                'usersCount' => User::all()->count(),
                'productsCount' => Product::all()->count()
            ];
        }
        if ('dispatcher' === $member->role) {
            $view = 'dispatcher.dashboard';
            $data = [
                'orders' => Order::where('status', '!=', 'rejected')
                    ->where('status', '!=', 'canceled')
                    ->orderBy('status', 'asc')
                    ->get()
            ];
        }
        if ('delivery_driver' === $member->role) {
            $view = 'delivery_driver.dashboard';
            $data = [
                'orders' => Order::where('delivery_driver_id', $member->id)
                    ->where(function ($query) { $query->where('status', 'dispatched')->orWhere('status', 'shipped'); })
                    ->where('delivery_driver_id', $member->id)
                    ->orderBy('status', 'desc')
                    ->get()
            ];
        }
        
        return view($view, $data);
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
     * Display the edit role screen.
     * 
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function updateUserRole(int $id): Response
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
    public static function home(): Response
    {
        if (request('search')) {
            $products = Product::where('name', 'like', '%'.request('search').'%')->paginate(12);
        } else {
            $products = Product::paginate(12);
        }

        return View::make('home', ['products' => $products, 'query' => request('search')]);
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
