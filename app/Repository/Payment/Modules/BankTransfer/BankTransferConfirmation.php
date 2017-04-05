<?php

namespace App\Repository\Payment\Modules\BankTransfer;

use Illuminate\Database\Eloquent\Model;

class BankTransferConfirmation extends Model
{
    //
    protected $fillable =[
        'invoice_number',
        'destination_bank',
        'account_number',
        'account_name',
        'customer_bank',
        'customer_account_number',
        'customer_account_name',
        'confirmed',
    ];
}
