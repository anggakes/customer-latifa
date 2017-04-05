<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/28/17
 * Time: 6:16 PM
 */

namespace App\Repository\Order\Data;


class Therapist
{
    public $name;
    public $handphone;
    public $id;
    public $email;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->phone_number = $data['handphone'];
    }
}