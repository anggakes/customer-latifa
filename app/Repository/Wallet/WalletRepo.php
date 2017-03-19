<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/5/17
 * Time: 8:34 PM
 */

namespace App\Repository\Wallet;


use App\Models\Wallet;
use App\Models\WalletHistory;

class WalletRepo implements WalletInterface
{
    public $userId;
    const TYPE_DEBIT = 'DEBIT';
    const TYPE_CREDIT = 'CREDIT';

    public function __construct($userId = null)
    {
        if($userId){
            $this->setUserId($userId);
        }
    }

    public function setUserId($userId){
        $this->userId = $userId;
    }

    public function credit($nominal)
    {
        if(!$this->hasCreated()){
            $this->create();
        }

        WalletHistory::create([
            "type"      => self::TYPE_CREDIT,
            "nominal"   => $nominal,
            'user_id'   => $this->userId
        ]);
        $wallet = Wallet::where('user_id', $this->userId);
        $wallet->increment('balance', $nominal);
        return  $wallet->get();
    }

    public function debit($nominal)
    {
        if(!$this->hasCreated()){
            $this->create();
        }

        WalletHistory::create([
            "type"      => self::TYPE_DEBIT,
            "nominal"   => $nominal,
            'user_id'   => $this->userId
        ]);

        $wallet = Wallet::where('user_id', $this->userId);
        $wallet->decrement('balance', $nominal);

        return $wallet->get();
    }

    public function get()
    {
        if(!$this->hasCreated()){
            $this->create();
        }

        return Wallet::where('user_id', $this->userId)->get();
    }

    public function getHistory($offset = 0, $limit = 10, $type = 'all'){

        if(!$this->hasCreated()){
            $this->create();
        }

        $wallet = WalletHistory::where('user_id', $this->userId);

        if($type == 'credit') $wallet->where('type', Self::TYPE_CREDIT);
        elseif($type == 'DEBIT') $wallet->where('type', Self::TYPE_DEBIT);

        return $wallet->limit($limit)->offset($offset)->get();
    }

    public function create()
    {
        return Wallet::create([
            "user_id"   => $this->userId,
            "balance"   => 0,
        ]);

    }

    public function hasCreated(){
        $wallet = Wallet::where('user_id', $this->userId)->count();
        return $wallet > 0 ? true : false;
    }
}