<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/17/17
 * Time: 12:51 AM
 */

namespace App\Repository\Order;


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
            "label_therapist"   => 'Menuju lokasi',
            'index'             => 4
        ],
        [
            'id'                => 'SERVICES_START',
            "label_customer"    => 'Layanan di mulai',
            "label_therapist"   => 'mulai layanan',
            'index'             => 5
        ],
        [
            'id'                => 'SERVICES_FINISHED',
            "label"             => 'Layanan selesai',
            "label_therapist"   => 'layanan selesai',
            'index'             => 6
        ]
    ];

    public function getInitStatus(){
        return self::STATUS[0];
    }

    public function getNextStatus(){

    }

}