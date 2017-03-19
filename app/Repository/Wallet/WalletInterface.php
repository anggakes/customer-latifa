<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/5/17
 * Time: 11:59 PM
 */

namespace App\Repository\Wallet;


interface WalletInterface
{
    const HISTORY_TYPE_DEBIT    = 'debit';
    const HISTORY_TYPE_CREDIT   = 'credit';

    public function credit($nominal);

    public function debit($nominal);

    public function get();

    public function create();

    public function setUserId($userId);

    public function getHistory($page, $limit);
}