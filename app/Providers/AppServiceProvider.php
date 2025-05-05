<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Observers\CartItemObserver;
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
        CartItem::observe(CartItemObserver::class);
    }
}
