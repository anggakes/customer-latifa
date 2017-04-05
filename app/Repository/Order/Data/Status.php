<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/28/17
 * Time: 6:44 PM
 */

namespace App\Repository\Order\Data;


class Status
{

    public $id;
    public $label_customer;
    public $label_therapist;
    public $index;


    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->label_therapist = $data['label_therapist'];
        $this->label_customer  = $data['label_customer'];
        $this->index = $data['index'];
    }

}