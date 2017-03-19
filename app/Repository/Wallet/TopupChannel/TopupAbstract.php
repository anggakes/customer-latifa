<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/11/17
 * Time: 1:29 PM
 */

namespace App\Repository\Wallet\TopupChannel;


abstract class TopupAbstract
{

    public abstract function validatingRequest($request);

    public abstract function topup();

    public abstract function getResult();
}