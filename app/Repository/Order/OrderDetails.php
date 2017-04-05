<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/17/17
 * Time: 1:24 AM
 */

namespace App\Repository\Order;


use App\Models\User;
use App\Repository\Order\Data\Customer;
use App\Repository\Order\Data\Items;
use App\Repository\Order\Data\Location;
use App\Repository\Order\Data\OrderStatus;
use App\Repository\Order\Data\Payment;
use App\Repository\Order\Data\Status;
use App\Repository\Order\Data\Therapist;
use App\Repository\Order\Data\Voucher;

class OrderDetails
{

    public $invoice_number = '';

    /** @var Customer $user */
    public $customer = null;

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

    /** @var Status $status */
    public $status = null;

    public $created_at = null;
    public $updated_at = null;

    public $converter = 1;

    /** @var Therapist $therapist */
    public $therapist = null;




}