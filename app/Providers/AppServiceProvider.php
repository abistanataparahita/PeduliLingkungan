<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        View::composer('admin.layouts.dashboard', function ($view) {
            $pendingOrdersCount = Order::query()
                ->where('status', 'pending')
                ->where('is_read', false)
                ->count();

            $view->with('pendingOrdersCount', $pendingOrdersCount);
        });
    }
}
