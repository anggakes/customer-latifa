<?php

namespace App\Repository\Order\Data;
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/26/17
 * Time: 12:25 AM
 */
class Items
{

    public $service_id;
    public $name;
    public $qty;
    public $price;
    public $point_price;

    public function __construct($data)
    {
        $this->service_id   = $data['service_id'] ?: '';
        $this->name         = $data['name'] ?: null;
        $this->qty          = $data['qty'] ?: 0;
        $this->point_price  = $data['point_price'] ?: 0;
        $this->price  = $data['price'] ?: 0;
    }

}