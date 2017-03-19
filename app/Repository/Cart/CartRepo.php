<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/12/17
 * Time: 8:37 AM
 */

namespace App\Repository\Cart;


use App\Repository\Order\OrderInterface;
use App\Repository\Wallet\PointConversion;
use MongoDB\Client;

class CartRepo implements CartInterface
{

    public $userId;

    /** @var  Client $collection*/
    public $collection;

    public static $cart;

    public function init($userId)
    {
        $this->userId = $userId;
        $this->collection  = (new Client())->latifa->cart;

        // check if cart has created
        $this->getCart();
    }

    public function countTotal(){

        $cart =  $this->collection->findOne(['_id'=> $this->userId]);

        $subtotal = 0;
        $total = 0;

        // count subtotal
        foreach ($cart->items as $item){
            $qty = $item->qty ?: 0;
            $price = $item->point_price ?: 0;
            $subtotal += ($qty*$price);
        }
        $voucherNominal = 0;

        if($cart->voucher){
            $voucherNominal = $cart->voucher->nominal;
        }

        $total += $subtotal;
        $total -= $voucherNominal;


        $this->collection->updateOne(['_id'=> $this->userId], ['$set' => ['subtotal' => $subtotal ]]);
        $this->collection->updateOne(['_id'=> $this->userId], ['$set' => ['total' => $total ]]);

    }

    public function add($product, $qty)
    {

        $this->collection->updateOne(['_id'=> $this->userId], ['$pull' => ['items' => ['service_id' => $product->service_id]]]);

        if($qty > 0){
            $items = [
                'service_id'=> $product->service_id,
                'product'   => $product->toArray(),
                'qty'       => $qty,
                'point_price' => PointConversion::rupiahToPoint($product->price)
            ];

            $k = $this->collection->updateOne(['_id'=> $this->userId], ['$push' => ['items' => $items]]);
        }

    }

    public function location($data)
    {
        $this->collection->updateOne(['_id'=> $this->userId], ['$set' =>
            ['location' => $data]
        ]);
    }

    public function date($data){
        $this->collection->updateOne(['_id'=> $this->userId], ['$set' =>
            ['date' => $data]
        ]);
    }

    public function getCart()
    {

        $cart =  $this->collection->findOne(['_id'=> $this->userId]);

        if(!$cart){
            $cart = new CartDetails($this->userId);
            $this->collection->insertOne($cart);
            $cart =  $this->collection->findOne(['_id'=> $this->userId]);
        }


        $this->countTotal();

        static::$cart = $cart;

        return $cart;

    }


    public function voucher($voucherCode)
    {
        // dummy voucher
        $data = [
            'voucher_code'  => $voucherCode,
            'nominal'       => 10,
            'note'          => '',
        ];

        $this->collection->updateOne(['_id'=> $this->userId], ['$set' =>
            ['voucher' => $data]
        ]);
    }

    public function removeVoucher()
    {
        $this->collection->updateOne(['_id'=> $this->userId], ['$set' => ['voucher' => null] ]);

    }

    public function destroy(){
        $this->collection->deleteOne(['_id'=> $this->userId]);
    }

}