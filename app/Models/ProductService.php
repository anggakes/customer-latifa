<?php

namespace App\Models;

use App\Repository\Wallet\PointConversion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class ProductService extends Model implements HasMedia
{
    //
    use HasMediaTrait;

    use SoftDeletes;

    public static $isConvertPoint = false;

    protected $dates = ['deleted_at'];

    protected $table = 'product_services';

    protected $fillable = [
        'name',
        'service_id',
        'regular_price',
        'sale_price',
        'description'
    ];

    protected $hidden = [];

    protected $appends = [
        'discount',
        'price'
    ];

    public function getDiscountAttribute(){
        $regPrice = $this->attributes['regular_price'];
        $salePrice = $this->attributes['sale_price'];
        $disc = ($regPrice - $salePrice)/$regPrice * 100;
        $disc = ($disc > 0 AND $disc < 100) ? $disc : 0;

        return (int) ceil($disc);
    }

    public function getPriceAttribute(){

        $regPrice = $this->attributes['regular_price'];
        $salePrice = $this->attributes['sale_price'];

        if($salePrice > 0 and $salePrice < $regPrice){
            $price =  $salePrice;
        }else{
            $price =  $regPrice;
        }

        return static::$isConvertPoint ? PointConversion::rupiahToPoint($price) : $price;

    }

    public function categories(){
        return $this->belongsToMany('App\Models\Category', 'product_service_category', 'product_service_id', 'category_id');
    }


    public function getRegularPriceAttribute($value){
        return static::$isConvertPoint ? PointConversion::rupiahToPoint($value) : $value;
    }


    public function getSalePriceAttribute($value){
        return static::$isConvertPoint ? PointConversion::rupiahToPoint($value) : $value;
    }

}
