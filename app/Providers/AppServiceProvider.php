<?php

namespace App\Providers;

use App\Repository\Cart\CartRepo;
use App\Repository\Order\OrderRepo;
use App\Repository\Payment\PaymentRepo;
use App\Repository\ProductService\ProductServiceRepo;
use App\Repository\Wallet\WalletRepo;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('App\Repository\Wallet\WalletInterface', function(){

            return new WalletRepo();

        });

        $this->app->bind('App\Repository\ProductService\ProductServiceInterface', function(){

            return new ProductServiceRepo();

        });

        $this->app->bind('App\Repository\Cart\CartInterface', function(){

            return new CartRepo();

        });

        $this->app->bind('App\Repository\Order\OrderInterface', function(){

            return new OrderRepo();

        });

        $this->app->bind('App\Repository\Payment\PaymentInterface', function(){

            return new PaymentRepo();

        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'App\Repository\Wallet\WalletInterface',
            'App\Repository\ProductService\ProductServiceInterface',
            'App\Repository\Cart\CartInterface',
            'App\Repository\Order\OrderInterface',
            'App\Repository\Payment\PaymentInterface',
        ];
    }
}
