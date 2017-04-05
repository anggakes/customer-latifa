<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/12/17
 * Time: 8:37 AM
 */

namespace App\Repository\Cart;


use App\Repository\Order\Data\Items;
use App\Repository\Order\Data\Location;
use App\Repository\Order\Data\Voucher;
use App\Repository\Order\Data\Payment;
use MongoDB\Client;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CartRepo implements CartInterface
{

    /** @var  Client $collection*/
    public $collection;

    public function __construct()
    {
        $this->collection  = (new Client())->latifa->cart;
    }

    public function addOrUpdateItem($userId, Items $item)
    {
        $cart =  $this->collection->findOne(['user_id'=> $userId]);

        if(!$cart) $this->createCart($userId);


        $this->collection->updateOne(['user_id'=> $userId], ['$pull' => ['items' => ['service_id' => $item->service_id]]]);

        if($item->qty > 0){

            $k = $this->collection->updateOne(['user_id'=> $userId], ['$push' => ['items' => $item]]);
        }

        $this->setSubtotal($userId);
        $this->removeVoucher($userId);
        $this->countTotal($userId);

    }


    public function countTotal($userId){

        $cart =  $this->getCart($userId);

        $total = 0;

        foreach ($cart->fee as $fee){
            $total += $fee['value'];
        }

        $this->collection->updateOne(['user_id'=> $userId], ['$set' => ['total' => $total ]]);

    }

    public function setSubtotal($userId){
        $cart =  $this->getCart($userId);

        $subtotal = 0;

        // count subtotal
        foreach ($cart->items as $item){
            $qty = $item->qty ?: 0;
            $price = $item->point_price ?: 0;
            $subtotal += ($qty*$price);
        }

        $this->setFee($userId, 'subtotal', $subtotal);

    }

    public function setFee($userId, $label, $value){

        $this->unsetFee($userId, $label);

        $this->collection->updateOne(['user_id'=> $userId], ['$push' => ['fee' =>
            [
                'label' => $label,
                'value' => $value
            ]
        ]]);
    }

    public function unsetFee($userId, $label){

        $this->collection->updateOne(['user_id'=> $userId], ['$pull' => ['fee' => [
                'label' => $label
        ]]]);
    }


    public function setLocation($userId, Location $location){


        $this->collection->updateOne(['user_id'=> $userId], ['$set' =>['location' => $location]]);

    }

    public function setSchedule($userId,$schedule){

        $this->collection->updateOne(['user_id'=> $userId], ['$set' =>['schedule' => $schedule]]);

    }

    public function setNote($userId,$note){

        $this->collection->updateOne(['user_id'=> $userId], ['$set' =>['note' => $note]]);

    }

    public function setPayment($userId, $channelId, $label){

        $paymentRepo = app()->make('App\Repository\Payment\PaymentInterface');
        $cart =  $this->getCart($userId);

        $convenienceFee = $paymentRepo->getConvenienceFee($channelId, $cart->total, ['unique_code' => $cart->unique_code]);

        $this->setFee($userId, 'payment_fee', $convenienceFee);

        $payment = new Payment(['channel_id' => $channelId, 'payment_fee' => $convenienceFee, 'label' => $label]);
        $this->collection->updateOne(['user_id'=> $userId], ['$set' => ['payment' => $payment]]);

        $this->removeVoucher($userId);
        $this->countTotal($userId);

        return $payment;

    }

    public function createCart($userId){

            $cart = new CartDetails();
            $cart->user_id = $userId;
            $cart->unique_code = UniqueCode::generate();
            $this->collection->insertOne($cart);
            $cart =  $this->collection->findOne(['user_id'=> $userId]);

            return $cart;
    }


    /**
     * @param $userId
     * @return CartDetails
     */

    public function getCart($userId)
    {

        $cart =  $this->collection->findOne(['user_id'=> $userId]);

        if(!$cart) $cart = $this->createCart($userId);

        // rebuild
        $cartDetails = new CartDetails();
        $cartDetails->user_id = $cart->user_id;
        $cartDetails->items         = $cart->items;
        $cartDetails->fee           = $cart->fee;
        $cartDetails->total         = $cart->total;
        $cartDetails->voucher       = $cart->voucher;
        $cartDetails->schedule      = $cart->schedule;
        $cartDetails->payment       = $cart->payment;
        $cartDetails->unique_code   = $cart->unique_code;
        $cartDetails->location      = $cart->location;
        $cartDetails->note          = isset($cart->note) ? $cart->note : '';

        return $cartDetails;

    }


    public function voucher($userId, $voucherCode)
    {
        if($voucherCode == '') {
            $this->removeVoucher($userId);
            $voucher = null;
        }else{

            if($voucherCode != 'testing') throw new BadRequestHttpException(
                'Voucher tidak tersedia'
            );
            // dummy voucher
            $voucher = new Voucher([
                'voucher_code'  => $voucherCode,
                'nominal'       => -10000,
                'note'          => '',
            ]);

            $this->setFee($userId, 'voucher', $voucher->nominal);

            $this->collection->updateOne(['user_id'=> $userId], ['$set' =>
                ['voucher' => $voucher]
            ]);
        }

        $this->countTotal($userId);

        return $voucher;
    }

    public function removeVoucher($userId)
    {
        $this->unsetFee($userId, 'voucher');
        $this->collection->updateOne(['user_id'=> $userId], ['$set' => ['voucher' => null] ]);

    }

    public function destroy($userId){
        $this->collection->deleteOne(['user_id'=> $userId]);
    }

}