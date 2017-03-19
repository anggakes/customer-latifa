<?php

namespace App\Http\Controllers\Cart;

use App\Models\ProductService;
use App\Repository\Cart\CartInterface;
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

        $this->cart->init(request()->user()->id);

        return [
            'cart' => $this->cart->getCart()
        ];
    }

    public function add()
    {
        $this->cart->init(request()->user()->id);

        $qty        = request('qty');
        $serviceId  = request('service_id');

        $p = ProductService::where('service_id', $serviceId)->first();

        if(!$p) throw new BadRequestHttpException('Product tidak ditemukan');

        $this->cart->add($p, $qty);

        $this->cart->countTotal();

        return [
            'cart' => $this->cart->getCart(),
            'added' => [
                'product'   => $p,
                'qty'       => $qty
            ]
        ];
    }


    public function remove()
    {
        $this->cart->init(request()->user()->id);

        $serviceId  = request('service_id');

        $p = ProductService::where('service_id', $serviceId)->first();

        if(!$p) throw new BadRequestHttpException('Product tidak ditemukan');

        $this->cart->add($p, 0);

        $this->cart->countTotal();

        return [
            'cart' =>$this->cart->getCart(),
            'removed' => [
                'product' => $p
            ]
        ];
    }


    public function location(){

        $data = request()->all();

        $this->cart->init(request()->user()->id);

        $this->cart->location($data);

        return [
            'cart' =>$this->cart->getCart(),
        ];

    }

    public function date(){
        $data = request('schedule');

        $this->cart->init(request()->user()->id);

        $this->cart->date($data);

        return [
            'cart' =>$this->cart->getCart(),
        ];
    }

    public function voucher(){
        $voucherCode = request('voucher_code');

        $this->cart->init(request()->user()->id);

        $this->cart->voucher($voucherCode);

        $this->cart->countTotal();

        return [
            'cart' =>$this->cart->getCart(),
        ];
    }

    public function removeVoucher(){

        $this->cart->init(request()->user()->id);

        $this->cart->removeVoucher();

        $this->cart->countTotal();

        return [
            'cart' =>$this->cart->getCart(),
        ];
    }

}
