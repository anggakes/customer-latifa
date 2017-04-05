<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/17/17
 * Time: 12:51 AM
 */

namespace App\Repository\Order;


use App\Repository\Order\Data\Status;

class OrderStatus
{


    const STATUS = [
        [
            'id'                => 'ORDER_CREATED',
            "label_customer"    => 'Order di buat',
            "label_therapist"   => 'Order di buat',
            'index'             => 1
        ],
        [
            'id'                => 'FIND_THERAPIST',
            "label_customer"    => 'Mencari Terapis',
            'label_therapist'   => 'Mencari Terapis',
            'index'             => 2
        ],
        [
            'id'                => 'ORDER_RECEIVED_BY_THERAPIST',
            "label_customer"    => 'Order telah diterima terapis',
            "label_therapist"   => 'Terima Order',
            'index'             => 3
        ],
        [
            'id'                => 'THERAPIST_ON_THE_WAY',
            "label_customer"    => 'Terapis sedang menuju lokasi Anda',
            "label_therapist"   => 'Mulai Perjalanan',
            'index'             => 4
        ],
        [
            'id'                => 'SERVICES_START',
            "label_customer"    => 'Layanan di Mulai',
            "label_therapist"   => 'Mulai Pelayanan',
            'index'             => 5
        ],
        [
            'id'                => 'SERVICES_FINISHED',
            "label_customer"    => 'Layanan Selesai',
            "label_therapist"   => 'Layanan Selesai',
            'index'             => 6
        ]
    ];

    /**
     * @return Data\OrderStatus
     */
    public function getInitStatus(){
        return new \App\Repository\Order\Data\OrderStatus(self::STATUS[0]);
    }

    public function getNextStatus($id){

        $status = $this->getStatus($id);
        return self::STATUS[$status['index']+1];

    }

    public function getStatus($id){

        foreach (self::STATUS as $status){
            if($status['id'] == $id) return $status;
        }
    }

}