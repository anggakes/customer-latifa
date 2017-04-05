<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/26/17
 * Time: 1:52 AM
 */

namespace App\Repository\Order\Data;


class OrderStatus
{

    public $id;
    public $label_customer;
    public $label_therapist;
    public $index;

    public function __construct($data)
    {
        $this->id = $data['id'] ?: '';
        $this->label_customer = $data['label_customer'] ?: '';
        $this->label_therapist = $data['label_therapist'] ?: '';
        $this->index = $data['index'] ?: '';
    }
}