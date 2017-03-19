<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/4/17
 * Time: 10:14 PM
 */

$api->group(['prefix'=>'v1', "middleware" => ['App\Http\Middleware\AfterResponse', 'App\Http\Middleware\BeforeRequest']], function ($api){

    $api->post("/register", 'App\Http\Controllers\Auth\AuthController@register');
    $api->post("/login",'App\Http\Controllers\Auth\AuthController@login' );
    $api->post("/auth/token", 'App\Http\Controllers\Auth\AuthController@refreshToken');

    $api->get("/boot", 'App\Http\Controllers\Boot\BootController@index');

    // authenticate route
    $api->group(["middleware"=> "jwt.auth"], function($api){

        /**
         *  PROFILE ROUTES
         */
        $api->get("/profile", 'App\Http\Controllers\Profile\ProfileController@index');
        $api->post("/profile", 'App\Http\Controllers\Profile\ProfileController@update');
        $api->post("/change_password", 'App\Http\Controllers\Profile\ProfileController@changePassword');

        /**
         * WALLET ROUTES
         */
        $api->get('/wallet', 'App\Http\Controllers\Wallet\WalletController@index');
        $api->get('/wallet/history', 'App\Http\Controllers\Wallet\WalletController@history');


        /**
         * Service ROUTES
         */
        $api->get('/services', 'App\Http\Controllers\Service\ServiceController@index');



        /** CART ROUTES */
        $api->get('/cart', 'App\Http\Controllers\Cart\CartController@index');
        $api->post('/cart/add', 'App\Http\Controllers\Cart\CartController@add');
        $api->post('/cart/remove', 'App\Http\Controllers\Cart\CartController@remove');
        $api->post('/cart/location', 'App\Http\Controllers\Cart\CartController@location');
        $api->post('/cart/date', 'App\Http\Controllers\Cart\CartController@date');
        $api->post('/cart/voucher', 'App\Http\Controllers\Cart\CartController@voucher');
        $api->post('/cart/remove_voucher', 'App\Http\Controllers\Cart\CartController@removeVoucher');

        /** ORDER ROUTES */

        $api->post('/checkout', 'App\Http\Controllers\Order\OrderController@checkout');
    });

});