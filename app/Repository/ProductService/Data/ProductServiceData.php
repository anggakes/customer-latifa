<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 4/16/17
 * Time: 11:24 PM
 */

namespace App\Repository\ProductService\Data;


class ProductServiceData
{
    public $service_id;
    public $regular_price;
    public $sale_price;
    public $price;
    public $discount;
    public $name;
    public $description;
    public $categories = [];
    public $images;

    public function __construct($data)
    {
        if(!$data->service_id) throw new \Exception("service_id not set");
        $this->service_id = $data->service_id ;
        $this->regular_price = (!$data->regular_price) ?: 0;
        $this->sale_price = (!$data->sale_price) ?: 0;
        $this->price = (!$data->price) ?: 0;
        $this->discount = (!$data->discount) ?: 0?: "";
        $this->name = (!$data->name) ?: "";
        $this->description = (!$data->description) ?: "";

        $categories = [];
        foreach($data->categories as $cat){
            $categories[] = new ProductServiceCategory($cat);
        }

        $mediaItems = $data->getMedia('service_product');

        $images = [];
        foreach ($mediaItems as $image){
            $images[] = app()->make('url')->to($image->getUrl());
        }


        $this->images = $images;
        $this->categories = $categories;

    }
}