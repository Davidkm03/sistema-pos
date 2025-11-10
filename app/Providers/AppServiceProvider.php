<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Models\Category;
use App\Models\Quote;
use App\Models\Sale;
use App\Observers\ProductObserver;
use App\Observers\CategoryObserver;
use App\Observers\QuoteObserver;
use App\Observers\SaleObserver;

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
        // Registrar observers
        Product::observe(ProductObserver::class);
        Category::observe(CategoryObserver::class);
        Quote::observe(QuoteObserver::class);
        Sale::observe(SaleObserver::class);
        
        // Definir una Gate para super-admin que siempre retorna true
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });
    }
}
