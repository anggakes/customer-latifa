<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/22/17
 * Time: 9:31 AM
 */

namespace App\Repository\Payment;


use App\Repository\Order\OrderDetails;
use App\Repository\Order\OrderInterface;
use App\Repository\Payment\Models\Payment;
use App\Repository\Payment\Modules\BankTransfer\BankTransfer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PaymentRepo implements PaymentInterface
{
    // Key nya harus sama dengan ID

    public $paymentChannel = [
        'BANKTRANSFER' => BankTransfer::class
    ];

    const STATUS_PENDING = "PENDING";
    const STATUS_WAITING_CONFIRMATION = "WAITING_CONFIRMATION";
    const STATUS_SETTLEMENT = "SETTLEMENT";
    const STATUS_FAILED = "FAILED";



    public function makeChannelObject($channelId){

        if(!isset($this->paymentChannel[$channelId])) throw new BadRequestHttpException('Channel tidak ditemukan');

        $class = $this->paymentChannel[$channelId];
        $object = new $class();

        return $object;
    }

    public function getConvenienceFee($channelId, $total, $additional){

        $channel = $this->makeChannelObject($channelId);

        return $channel->getConvenienceFee($total, $additional);
    }

    public function getChannel($total, $additional){

        $channel = [];

        foreach ($this->paymentChannel as $id => $class){

            $channel[] = $this->makeChannelObject($id)->getInfo($total, $additional);
        }

        return $channel;

    }


    public function create(OrderDetails $order){
        $paymentModel = Payment::create([
            'channel_id' => $order->payment->channel_id,
            'payment_status' => self::STATUS_PENDING,
            'created_date' => date('Y-m-d H:i:s'),
            'invoice_number' => $order->invoice_number
        ]);
    }


    public function confirmation($invoiceNumber, $channelId, $data){

        $channel = $this->makeChannelObject($channelId);

        $orderRepo = app()->make('App\Repository\Order\OrderInterface');
        $order = $orderRepo->getOrder($invoiceNumber);

        return $channel->confirmation($order, $data);

    }

    public function notify($invoiceNumber, $channelId, $data){

        $channel = $this->makeChannelObject($channelId);

        $orderRepo = app()->make('App\Repository\Order\OrderInterface');
        $order = $orderRepo->getOrder($invoiceNumber);

        return $channel->notify($order, $data);

    }

    public function getAdditionalData($channelId){

        $channel = $this->makeChannelObject($channelId);

        return $channel->getAddtionalData();
    }

}