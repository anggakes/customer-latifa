<?php

namespace App\Http\Controllers\Payment;

use App\Repository\Payment\PaymentInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentServiceController extends Controller
{
    //

    public function notify(PaymentInterface $payment){

        $request = request()->all();
        if(!$request) $request = json_decode(request()->getContent(), true);
        $invoiceNumber = $request['invoice_number'];
        $channelId = $request['channel_id'];
        $data = [];


        return  response()->json($payment->notify($invoiceNumber, $channelId, $data));

    }



}
