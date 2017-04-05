<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/14/17
 * Time: 8:04 AM
 */

namespace App\Repository\Cart;


use App\Repository\Order\Data\Items;
use App\Repository\Order\Data\Location;
use App\Repository\Order\Data\Payment;
use App\Repository\Order\Data\Voucher;

class CartDetails
{

    public $user_id;

    /** @var Items[] $items */
    public $items = [];

    public $fee = [
        [
            'label' => 'subtotal',
            'value' => 0
        ]
    ];

    public $total = 0;

    /** @var Voucher $voucher  */
    public $voucher = null;

    /** @var Location $location */
    public $location = null;

    public $schedule = null;

    /** @var Payment $payment */
    public $payment = null;

    public $unique_code = 0;

    public $note = '';
}
