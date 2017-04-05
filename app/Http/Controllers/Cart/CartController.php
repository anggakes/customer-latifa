<?php

namespace App\Http\Controllers\Cart;

use App\Models\ProductService;
use App\Repository\Cart\CartInterface;
use App\Repository\Order\Data\Items;
use App\Repository\Order\Data\Location;
use App\Repository\Wallet\PointConversion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CartController extends Controller
{
    //
    public $cart;

    public function __construct(CartInterface $cart)
    {

        $this->cart = $cart;
    }

    public function index(){

        return ['cart' => $this->cart->getCart(request()->user()->id)];
    }

    public function add()
    {

        $qty        = request('qty');
        $serviceId  = request('service_id');
        $userId     = request()->user()->id;

        $p = ProductService::where('service_id', $serviceId)->first();

        if(!$p) throw new BadRequestHttpException('Product tidak ditemukan');

        // rebuild

        $item = new Items([
            'service_id'        => $p->service_id,
            'name'              => $p->name,
            'price'             => $p->price,
            'point_price'       => PointConversion::rupiahToPoint($p->price),
            'qty'               => $qty,
        ]);

        $this->cart->addOrUpdateItem($userId, $item);

        return [
            'cart'  => $this->cart->getCart($userId),
            'item' => $item
        ];
    }

    public function remove()
    {

        $serviceId  = request('service_id');
        $userId     = request()->user()->id;

        $p = ProductService::where('service_id', $serviceId)->first();

        if(!$p) throw new BadRequestHttpException('Product tidak ditemukan');

        $item = new Items([
            'service_id'        => $p->service_id,
            'name'              => $p->name,
            'price'             => $p->price,
            'point_price'       => PointConversion::rupiahToPoint($p->price),
            'qty'               => 0,
        ]);

        $this->cart->addOrUpdateItem($userId, $item);

        return [
            'cart'  => $this->cart->getCart($userId),
            'item' => $item
        ];
    }



    public function voucher(){
        $voucherCode = request('voucher_code');

        $userId     = request()->user()->id;

        $voucher = $this->cart->voucher($userId, $voucherCode);

        return [
            'cart'  => $this->cart->getCart($userId),
            'voucher' => $voucher
        ];
    }

    public function location(){

        $location   = new Location([
            'lat' => request('lat'),
            'lng' => request('lng'),
            'address' => request('address'),
        ]);
        $userId     = request()->user()->id;


        $this->cart->setlocation($userId, $location);

        return [
            'cart'  => $this->cart->getCart($userId),
            'locaion' => $location
        ];
    }

    public function payment(){

        $channelId      = request('channel_id');
        $userId         = request()->user()->id;
        $label          = request('label');

        $payment = $this->cart->setPayment($userId, $channelId, $label);

        return [
            'cart'  => $this->cart->getCart($userId),
            'payment' => $payment
        ];
    }

}
