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




});