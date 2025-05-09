<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Observers\CartItemObserver;
use App\Repositories\CartRepository;
use App\Services\BillingItems\BillingItemsService;
use App\Services\BillingItems\CartItemsProvider;
use App\Services\BillingItems\StripeItemsParser;
use App\Services\Payment\PayingService;
use App\Services\Payment\StripePayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->when(\App\Http\Controllers\PaymentController::class)
            ->needs(BillingItemsService::class)
            ->give(function ($app) {
                return new BillingItemsService(
                    new StripeItemsParser,
                    new CartItemsProvider(new CartRepository),
                    Auth::id() // Pass the authenticated user ID
                );
            });

        $this->app->when(\App\Http\Controllers\PaymentController::class)
            ->needs(PayingService::class)
            ->give(function ($app) {
                return new PayingService(new StripePayment);
            });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        CartItem::observe(CartItemObserver::class);
    }
}
