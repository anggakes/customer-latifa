<?php

namespace App\Http\Controllers\Wallet;

/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/9/17
 * Time: 5:40 PM
 */

use App\Http\Controllers\Controller;
use App\Repository\Wallet\TopupChannel\TopupAbstract;
use App\Repository\Wallet\WalletInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Validator;

class WalletController extends Controller
{

    /** @var \App\Repository\Wallet\WalletRepo $wallet */
    private $wallet;

    /**
     * WalletController constructor.
     * @param WalletInterface $wallet
     */
    public function __construct(WalletInterface $wallet)
    {
        $this->wallet = $wallet;
    }


    public function index(){
        // because cannot use in constructor
        $this->wallet->setUserId(request()->user()->id);

        return $this->wallet->get();

    }

    public function topup(){

        $validator = $this->validator(request()->all());

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->getMessageBag()->first());
        }

        // creating the class
        // throw exception from class if something error
        $channelClass = request('channel');
        /** @var TopupAbstract $channel */
        $channel = new $channelClass();
        $channel->validatingRequest(request()->all());
        $channel->topup();

        // I asume everything is ok
        return $channel->getResult();
    }

    public function history(){

        $this->wallet->setUserId(request()->user()->id);
        $page   = request('page') ?: 1;
        $limit  = request('limit') ?: 10;

        return $this->wallet->getHistory(($page-1)*$limit,$limit);
    }


    private function validator($data){
        return Validator::make($data, [
            'channel'      => 'required|between:3,255',
        ]);
    }

}