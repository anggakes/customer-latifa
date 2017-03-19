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

    public static function pointToRupiah($point){
        $conv = config("app.point_conversion");

        return $point*$conv;
    }

    public static function rupiahToPoint($rupiah){
        // di bulat kan keatas

        $conv = config("app.point_conversion");

        return ceil($rupiah/$conv);
    }
}