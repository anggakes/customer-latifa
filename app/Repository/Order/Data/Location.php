<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/26/17
 * Time: 1:00 AM
 */

namespace App\Repository\Order\Data;


class Location
{
    public $lat;
    public $lng;
    public $address;
    public $additional_fee = 0 ;

    public function __construct($data)
    {
        $this->lat = $data['lat'] ?: '';
        $this->lng = $data ['lng'] ?: '';
        $this->address = $data['address'] ?: '';

    }
}