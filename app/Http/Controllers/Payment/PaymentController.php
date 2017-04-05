<?php

namespace App\Http\Controllers\Payment;

use App\Repository\Payment\PaymentInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    //

    public function channel(PaymentInterface $payment){

        $request = request()->all();
        $total = $request['total'];
        $uniqueCode = $request['unique_code'];
        $additional = [
            'unique_code' => $uniqueCode
        ];

        return $payment->getChannel($total, $additional);
    }

    public function confirmation(PaymentInterface $payment){

        $request = json_decode(request()->getContent(), true);
        $channelId      = $request['channel_id'];
        $data           = $request['data'];
        $invoiceNumber  = $request['invoice_number'];

        return $payment->confirmation($invoiceNumber, $channelId, $data);

    }

    public function getAdditionalData($channelId, PaymentInterface $payment){

        return $payment->getAdditionalData($channelId);

    }



}
