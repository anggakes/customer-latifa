<?php

namespace App\Http\Controllers\Order;

use App\Repository\Cart\CartInterface;
use App\Repository\Order\OrderInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    //

    public function checkout(CartInterface $cart, OrderInterface $order){

        $userId = request()->user()->id;
        $cart->init($userId);

        return $order->checkout($userId, $cart);

    }
}
