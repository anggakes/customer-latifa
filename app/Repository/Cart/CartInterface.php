<?php

namespace App\Repository\Cart;
use App\Repository\Order\OrderInterface;
use MongoDB\Client;

/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/12/17
 * Time: 8:36 AM
 */
interface CartInterface
{

    public function init($userId);

    public function countTotal();

    public function add($product, $qty);

    public function getCart();

    public function location($data);

    public function date($data);

    public function voucher($voucherCode);

    public function removeVoucher();

    public function destroy();
    
}