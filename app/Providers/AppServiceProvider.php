<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{Order, User, Product};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->singleton('adminDashboardView', function () {
            $usersCount = User::all()->count();
            $productsCount = Product::all()->count();

            return view('admin.dashboard', ['usersCount' => $usersCount, 'productsCount' => $productsCount]);
        });

        app()->singleton('dispatcherDashboardView', function () {
            $orders = Order::where('status', '!=', 'rejected')->where('status', '!=', 'canceled')->orderBy('status', 'asc')->get();

            return view('dispatcher.dashboard', ['orders' => $orders]);
        });

        app()->singleton('deliveryDriverDashboardView', function () {
            $whereCallback = function ($query) {
                $query->where('status', 'dispatched')->orWhere('status', 'shipped');
            };
            $orders = Order::where('delivery_driver_id', $member->id)->where($whereCallback)->where('delivery_driver_id', $member->id)->orderBy('status', 'desc')->get();

            return view('delivery_driver.dashboard', ['orders' => $orders]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected function adminDashboardView()
    {
        
    }
}
