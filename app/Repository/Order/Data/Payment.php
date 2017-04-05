<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/28/17
 * Time: 6:17 PM
 */

namespace App\Repository\Order\Data;


class Payment
{
    public $channel_id;
    public $payment_fee;
    public $label;

    public function __construct($data)
    {
        $this->channel_id = $data['channel_id'];
        $this->payment_fee = $data['payment_fee'];
        $this->label = $data['label'];
    }
}