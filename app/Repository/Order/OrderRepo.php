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
use App\Repository\Order\Data\Customer;
use App\Repository\Order\Data\Therapist;
use App\Utils\Helpers;
use MongoDB\Client;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderRepo implements OrderInterface
{

    public $collection;

    /** @var  OrderStatus $orderStatus */


    public function __construct()
    {
        $this->collection  = (new Client())->latifa->order;

    }

    public function createOrder($userId, CartInterface $cartRepo, $additional){


        /** @var CartDetails $cart */
        $cart           = $cartRepo->getCart($userId);

        // save order from cart;
        if(count($cart->items) <= 0) throw new BadRequestHttpException('Cart tidak boleh kosong');
        if($cart->location == null) throw new BadRequestHttpException('Lokasi tidak boleh kosong');
        if($cart->schedule == null) throw new BadRequestHttpException('jadwal tidak boleh kosong');
        if($cart->payment == null) throw new BadRequestHttpException('Pembayaran tidak boleh kosong');


        $orderData = $this->_buildOrder($userId, $cart, $additional);

        $this->collection->insertOne($orderData);

        // write history,
        OrderHistory::write($orderData->invoice_number, $orderData->status->id);

        // notify Payment
        $payment = app()->make('App\Repository\Payment\PaymentInterface');

        $payment->create($orderData);

        $cartRepo->destroy($userId);

        return $orderData;

    }

    private function _buildOrder($userId, CartDetails $cart, $additional){

        $orderDetails   = new OrderDetails();
        // key
        $orderDetails->invoice_number   = $this->_makeInvoiceNumber($userId);
        $orderDetails->customer             = new Customer(User::find($userId)->toArray());

        // cart info
        $orderDetails->items        = $cart->items;
        $orderDetails->voucher      = $cart->voucher;
        $orderDetails->schedule     = new \MongoDB\BSON\UTCDateTime(new \DateTime($cart->schedule));
        $orderDetails->location     = $cart->location;
        $orderDetails->payment      = $cart->payment;
        $orderDetails->unique_code  = $cart->unique_code;

        // total and subtotal
        $orderDetails->fee      = $cart->fee;
        $orderDetails->total    = $cart->total;

        // date
        $orderDetails->created_at = new \MongoDB\BSON\UTCDateTime(new \DateTime());
        $orderDetails->updated_at = new \MongoDB\BSON\UTCDateTime(new \DateTime());

        // conversion
        $orderDetails->converter = config("app.point_conversion");

        //status
        $orderStatus = new OrderStatus();
        $orderDetails->status = $orderStatus->getInitStatus();

        // note
        $orderDetails->note     = $cart->note;

        return $orderDetails;

    }

    public function getOrder($invoiceNumber){
        $order = $this->collection->findOne(['invoice_number' => $invoiceNumber]);

        return $this->_buildOrderDetailsFromOrder($order);
    }

    private function _buildOrderDetailsFromOrder($order){

        $orderDetails = new OrderDetails();

        $orderDetails->invoice_number   = $order->invoice_number;
        $orderDetails->customer         = $order->customer;

        // cart info
        $orderDetails->items        = $order->items;
        $orderDetails->voucher      = $order->voucher;
        $orderDetails->schedule     = $order->schedule->toDateTime()->format('d-m-Y H:i');
        $orderDetails->location     = $order->location;
        $orderDetails->payment      = $order->payment;
        $orderDetails->unique_code  = $order->unique_code;

        // total and subtotal
        $orderDetails->fee      = $order->fee;
        $orderDetails->total    = $order->total;

        // date
        $orderDetails->created_at = $order->created_at->toDateTime()->format('d-m-Y H:i');
        $orderDetails->updated_at = $order->updated_at->toDateTime()->format('d-m-Y H:i');

        // conversion
        $orderDetails->converter = $order->converter;

        //status
        $orderDetails->status = $order->status;

        // payment
        $orderDetails->payment  = $order->payment;

        // therapist
        $orderDetails->therapist    = $order->therapist;

        $orderDetails->note  = $order->note;

        return $orderDetails;
    }


    /**
     * @param $userId
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return OrderDetails[]
     */
    public function all($userId, $limit=10, $offset=0 ,$filter = []){

        $filter['user.id'] = (int) $userId;
        $option = ['limit' => (int) $limit, 'skip' => (int) $offset];

        $orderDetails = [];

        $data = $this->collection->find($filter, $option);

        foreach ($data as $order){
            $orderDetails[] = $this->_buildOrderDetailsFromOrder($order);
        }

        return $orderDetails;
    }


    public function setTherapist($invoiceNumber, Therapist $therapist){

        $this->collection->updateOne(['invoice_number'=> $invoiceNumber], ['$set' =>['therapist' => $therapist]]);
    }


    public function updateStatus($invoiceNumber, $data, $status = ''){

        $orderStatus = new OrderStatus();
        $order = $this->getOrder($invoiceNumber);

        if($status == '') {
            $newStatus = $orderStatus->getNextStatus($order->status->id);
        }else{
            $newStatus = $orderStatus->getStatus($status);
        }

        if(!$newStatus) throw new BadRequestHttpException('status tidak valid');

        // write history,
        OrderHistory::write($order->invoice_number, $order->status->id);


        $this->collection->updateOne(['invoice_number'=> $invoiceNumber], ['$set' =>['status' => $newStatus]]);

        // jalankan event update status ...

    }



    private function _makeInvoiceNumber($userId){

        //create order to create autonincrement id
        $order = Order::create([
            'user_id' => $userId
        ]);

        $romanDay    = Helpers::numberToRoman((int) date('d'));
        $romanMonth  = Helpers::numberToRoman((int) date('m'));

        return "INV/".date('Ymd')."/".$romanDay."/".$romanMonth."/".$order->id;
    }







}