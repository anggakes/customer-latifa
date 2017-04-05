<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/5/17
 * Time: 10:45 PM
 */

namespace App\Repository\Wallet;


class PointConversion
{

    public static function pointToRupiah($point, $convertion = 0){

        $conv = $convertion == 0 ? config("app.point_conversion") : $convertion;

        return $point*$conv;
    }

    public static function rupiahToPoint($rupiah, $convertion = 0){
        // di bulat kan keatas

        $conv = $convertion == 0 ? config("app.point_conversion") : $convertion;

        return ceil($rupiah/$conv);
    }
}