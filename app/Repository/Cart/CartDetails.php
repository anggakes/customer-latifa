<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/14/17
 * Time: 8:04 AM
 */

namespace App\Repository\Cart;


class CartDetails
{
    public $_id;
    public $userId;
    public $items = [];
    public $subtotal = 0;
    public $total = 0;
    public $location = null;
    public $date     = null;
    public $voucher = null;

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->_id   = $userId;
    }
}