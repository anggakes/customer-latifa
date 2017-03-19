<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/17/17
 * Time: 12:45 AM
 */

namespace App\Repository\Order;


use App\Repository\Cart\CartInterface;

interface OrderInterface
{

    public function checkout($userId, CartInterface $cart);

    public function cancel($orderId);

    public function nextStatus($orderId);

    public function addService($orderId, $data);

    public function get($orderId);

}