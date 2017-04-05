<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/26/17
 * Time: 12:26 AM
 */

namespace App\Repository\Order\Data;


class Voucher
{

    public $voucher_code;
    public $nominal;
    public $note;

    public function __construct($data){
        $this->voucher_code = $data['voucher_code'] ?: '';
        $this->nominal      = $data['nominal'] ?: 0;
        $this->note         = $data['note'] ?: '';
    }
}