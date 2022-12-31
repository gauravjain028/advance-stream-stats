<?php

namespace App\Providers;

use App\Services\Gateways\Braintree;
use Braintree\Gateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Braintree::class, function ($app) {
            return new Braintree([
                'environment' => env('BRAINTTREE_MERCHANT_ENV'),
                'merchantId' => env('BRAINTTREE_MERCHANT_ID'),
                'publicKey' => env('BRAINTTREE_PUBLIC_KEY'),
                'privateKey' => env('BRAINTTREE_PRIVATE_KEY')
            ]);
        });
    }
}
