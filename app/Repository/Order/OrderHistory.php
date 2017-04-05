<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/21/17
 * Time: 7:19 AM
 */

namespace App\Repository\Order;


use MongoDB\Client;

class OrderHistory
{
    public static function write($invoiceNumber, $status){

        $collection  = (new Client())->latifa->orderHistory;
        $orderHistory = new OrderHistoryDetails();
        $orderHistory->invoiceNumber = $invoiceNumber;
        $orderHistory->status = $status;
        $orderHistory->timestamps = new \MongoDB\BSON\UTCDateTime(new \DateTime());

        return $collection->insertOne($orderHistory);
    }

}