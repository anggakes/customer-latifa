<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/11/17
 * Time: 1:35 PM
 */

namespace App\Repository\Wallet\TopupChannel\CouponCode;


use App\Repository\Wallet\TopupChannel\TopupAbstract;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Validator;

class CouponCode extends TopupAbstract
{
    public $couponCode;

    public function validatingRequest($request)
    {

        $validator = $this->validator($request);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->getMessageBag()->first());
        }

        $this->couponCode = $request['coupon_code'];

    }

    public function topup()
    {
        // validating coupon code'


    }

    public function getResult()
    {
        // TODO: Implement getResult() method.
    }

    public function generateCoupon(){

    }

    private function validator($data){
        return Validator::make($data, [
            'channel'      => 'required|between:3,255',
            'coupon_code'  => 'required|between:3,255',
        ]);
    }
}