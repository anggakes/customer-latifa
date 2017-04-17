<?php

namespace App\Http\Controllers\Order;

use App\Repository\Order\Data\Therapist;
use App\Repository\Order\OrderInterface;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class OrderServiceController extends Controller
{
    //

    public function therapist(OrderInterface $order){
        $name = request('name');
        $handphone = request('handphone');
        $id = request('id');
        $email = request('email');
        $invoiceNumber = request('invoice_number');

        $therapist = new Therapist([
            'name' => $name,
            "handphone" => $handphone,
            'id' => $id,
            'email' => $email
        ]);

        $order->setTherapist($invoiceNumber, $therapist);

        $order->updateStatus($invoiceNumber, [], 'ORDER_RECEIVED_BY_THERAPIST');

        return response()->json($order->getOrder($invoiceNumber));
    }

    public function onTheWay(OrderInterface $order){
        $invoiceNumber = request('invoice_number');

        $order->updateStatus($invoiceNumber, [], 'THERAPIST_ON_THE_WAY');

        return response()->json($order->getOrder($invoiceNumber));
    }

    public function start(OrderInterface $order){
        $invoiceNumber = request('invoice_number');

        $order->updateStatus($invoiceNumber, [], 'SERVICES_START');

        return response()->json($order->getOrder($invoiceNumber));
    }

    public function findTherapist(OrderInterface $order){
        $invoiceNumber = request('invoice_number');

        $order->updateStatus($invoiceNumber, [], 'FIND_THERAPIST');

        return response()->json($order->getOrder($invoiceNumber));
    }

    public function getOrder(OrderInterface $order){
        $invoiceNumber = request('invoice_number');

        return response()->json($order->getOrder($invoiceNumber));
    }

}
