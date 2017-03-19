<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/17/17
 * Time: 12:45 AM
 */

namespace App\Repository\Order;


use App\Models\Order;
use App\Models\User;
use App\Repository\Cart\CartDetails;
use App\Repository\Cart\CartInterface;
use App\Repository\Wallet\PointConversion;
use App\Utils\Helpers;
use MongoDate;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;

class OrderRepo implements OrderInterface
{

    public $collection;

    /** @var  CartDetails $cart */
    public $cart;

    /** @var  OrderStatus $orderStatus */
    public $orderStatus;

    public function __construct()
    {
        $this->collection  = (new Client())->latifa->order;
        $this->orderStatus = new OrderStatus();
    }

    public function checkout($userId, CartInterface $cart){

        // save order from cart;
        // first ensure
        $this->cart = $cart->getCart();

        $orderData = $this->_buildOrder();
        $this->collection->insertOne($orderData);

        $order = $this->collection->findOne(['invoiceNumber' => $orderData->invoiceNumber]);

        $cart->destroy();

        return $order;

    }

    private function _buildOrder(){

        $orderDetails = new OrderDetails();
        // key
        $orderDetails->invoiceNumber = $this->_makeInvoiceNumber();
        $orderDetails->user = User::find($this->cart->userId)->toArray();

        // cart info
        $orderDetails->items = $this->cart->items;
        $orderDetails->date  = $this->cart->date;
        $orderDetails->voucher = $this->cart->voucher;
        $orderDetails->location = $this->cart->location;

        // total and subtotal
        $orderDetails->subtotal = PointConversion::pointToRupiah($this->cart->subtotal);
        $orderDetails->total    = PointConversion::pointToRupiah($this->cart->total);

        // date
        $orderDetails->created_at = new UTCDateTime(strtotime(date('Y-m-d H:i:s')));
        $orderDetails->updated_at = new UTCDateTime(strtotime(date('Y-m-d H:i:s')));

        // conversion
        $orderDetails->converter = config("app.point_conversion");

        //status
        $orderDetails->status = $this->orderStatus->getInitStatus()['id'];

        return $orderDetails;

    }

    private function _makeInvoiceNumber(){

        //create order to create autonincrement id
        $order = Order::create([
            'user_id' => $this->cart->userId
        ]);

        $romanDay    = Helpers::numberToRoman((int) date('d'));
        $romanMonth  = Helpers::numberToRoman((int) date('m'));

        return "INV/".date('Ymd')."/".$romanDay."/".$romanMonth."/".$order->id;
    }

    public function writeStatusHistory($invoiceNumber, $data){

    }



    public function cancel($orderId){

    }

    public function nextStatus($orderId){

    }

    public function addService($orderId, $data){

    }


    public function get($orderId){

    }



}