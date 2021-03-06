<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/4/17
 * Time: 10:14 PM
 */


$api->group(['prefix'=>'web_service', "middleware" => []], function ($api){

//    $api->post("/register", 'App\Http\Controllers\Auth\AuthController@register');
//

    /** Service Category */
    $api->post('product_services/category', 'App\Http\Controllers\Service\ServiceController@storeCategory');

    /** Service Route */

    $api->post('product_services', 'App\Http\Controllers\Service\ServiceController@store');



    $api->post('payment/notify', 'App\Http\Controllers\Payment\PaymentServiceController@notify');


    $api->post('order/therapist', 'App\Http\Controllers\Order\OrderServiceController@therapist');


    $api->post('order/status/ontheway', 'App\Http\Controllers\Order\OrderServiceController@ontheway');
    $api->post('order/status/start', 'App\Http\Controllers\Order\OrderServiceController@start');
    $api->post('order/status/findtherapist', 'App\Http\Controllers\Order\OrderServiceController@findTherapist');
    $api->post('order/get', 'App\Http\Controllers\Order\OrderServiceController@getOrder');


    $api->post('banner/store', 'App\Http\Controllers\Banner\BannerController@store');




});