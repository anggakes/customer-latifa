<?php

namespace App\Repository\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //

    protected $fillable = [
        'channel_id',
        'payment_status',
        'created_date',
        'process_date',
        'settlement_date',
        'invoice_number'
    ];


}
