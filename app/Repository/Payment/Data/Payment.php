<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/26/17
 * Time: 1:11 AM
 */

namespace App\Repository\Payment\Data;


class Payment
{
    public $channel_id;
    public $payment_status;
    public $payment_number;
    public $created_date;
    public $process_date;
    public $settlement_date;
    public $invoice_number;

    public function __construct($data)
    {
        $this->channel_id = $data['channel_id'];
        $this->payment_status = $data['payment_status'];
        $this->payment_number = $data['payment_number'];
        $this->created_date = $data['created_date'];
        $this->process_date = $data['process_date'];
        $this->settlement_date = $data['settlement_date'];
        $this->invoice_number = $data['invoice_number'];

    }
}