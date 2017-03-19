<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/17/17
 * Time: 1:24 AM
 */

namespace App\Repository\Order;


class OrderDetails
{

    public $invoiceNumber = '';
    public $user = null;
    public $items = [];
    public $location =null;
    public $date =null;
    public $subtotal = 0;
    public $total = 0;
    public $voucher = null;
    public $status = null;
    public $created_at = '';
    public $updated_at = '';
    public $statusHistory = [];
    public $payment = null;
    public $converter = 1;
    public $therapist = null;


}