<?php

namespace ItmoSoft\ProductCatalog;

use Illuminate\Support\ServiceProvider;

class ProductCatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/product-catalog.php', 'product-catalog');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/product-catalog.php' => config_path('product-catalog.php'),
        ], 'product-catalog-config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
