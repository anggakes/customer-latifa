<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/26/17
 * Time: 6:01 PM
 */

namespace App\Repository\Cart;


use Cache;
class UniqueCode
{

    public static function generate(){
        if (!Cache::has('unique_code_counter')) {
            Cache::forever('unique_code_counter', 1);
            return 1;
        }else{
            Cache::increment('unique_code_counter');
            $counter = Cache::get('unique_code_counter');
            if($counter > 999){
                Cache::forever('unique_code_counter', 1);
            }
            return $counter;
        }
    }


}