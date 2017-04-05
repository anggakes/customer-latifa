<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/22/17
 * Time: 9:34 AM
 */

namespace App\Repository\Payment\Modules\BankTransfer;


use App\Repository\Order\OrderDetails;
use App\Repository\Order\OrderInterface;
use App\Repository\Order\OrderRepo;
use App\Repository\Payment\Models\Payment;
use App\Repository\Payment\PaymentRepo;
use MongoDB\Client;

class BankTransfer
{

    public $waitingTime = 90; // minutes

    public $order;

    public $convenienceFee=0;

    public $id = 'BANKTRANSFER';

    public $label = 'Pembayaran Menggunakan Transfer Bank';

    public $collection;

    public $convenienceFeeAddOrMin = 'add';

    public function __construct()
    {
        $this->order = app()->make('App\Repository\Order\OrderInterface');
        $this->collection  = (new Client())->payment->bank_tansfer;

    }

    public function proses($order, $data){

        // event before proses payment


        // catat data dalam collection bank tansfer


        // notify order dan data

        // event after proses payment


    }

    public function notify(OrderDetails $order, $data){

        /** @var OrderRepo $order */
        $orderRepo = app()->make('App\Repository\Order\OrderInterface');
        $orderRepo->updateStatus($order->invoice_number, $data, 'FIND_THERAPIST');

        $conf = BankTransferConfirmation::where('invoice_number' , '=', $order->invoice_number)->first();
        $conf->confirmed = true;
        $conf->save();

        // update payment
        $payment = Payment::where('invoice_number' , '=', $order->invoice_number)->first();
        $payment->payment_status = PaymentRepo::STATUS_SETTLEMENT;
        $payment->settlement_date = date('Y-m-d H:i:s');
        $payment->save();
        
        return true;
    }

    public function confirmation(OrderDetails $order, $data){
        //extract :
        $destination_bank = $data['destination_bank'];
		$account_number = $data['account_number'];
		$account_name = $data['account_name'];
		$customer_bank = $data['customer_bank'];
		$customer_account_number = $data['customer_account_number'];
		$customer_account_name = $data['customer_account_name'];

        // save
        BankTransferConfirmation::create([
            'invoice_number' => $order->invoice_number,
            'destination_bank' => $destination_bank,
            'account_number' => $account_number,
            'account_name' => $account_name,
            'customer_bank' => $customer_bank,
            'customer_account_number' => $customer_account_number,
            'customer_account_name'=> $customer_account_name,
            'confirmed'=> false,
        ]);

        // update payment
        $payment = Payment::where('invoice_number' , '=', $order->invoice_number)->first();
        $payment->payment_status = PaymentRepo::STATUS_WAITING_CONFIRMATION;
        $payment->process_date = date('Y-m-d H:i:s');
        $payment->save();


    }

    public function getInfo($total, $addtional){

        return [
            'id'                => $this->id,
            'label'             => 'Bank Transfer',
            'convenience_fee'   => $this->getConvenienceFee($total, $addtional),
        ];
    }

    public function getHowTo(){
        return "
           <p>Lakukan Pembayaran ke rekening :</p>
           <ul>
            <li>
                Bank Tujuan : Bank BCA <br>
                Nomor Rekening : 0910929922 <br>
                Atas Nama : PT. latifa Home SPA
            </li>
           </ul>
        ";
    }


    public function getAddtionalData(){
        $data = [
            [
                'destination_bank'  => "Bank BCA",
                'account_number'    => "0910929922",
                "account_name"      => "PT. latifa Home SPA"
            ]
        ];

        return $data;
    }

    public function getWaitingTime(){

        return $this->waitingTime;

    }

    public function getConvenienceFee($total, $additional= []){
        $uniqueCode =  isset($additional['unique_code']) ? $additional['unique_code'] : 0;
        return ($this->convenienceFeeAddOrMin == 'min') ? $uniqueCode * -1 : $uniqueCode;
    }
}