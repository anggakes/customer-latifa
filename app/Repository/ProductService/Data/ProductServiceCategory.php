<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 4/16/17
 * Time: 11:34 PM
 */

namespace App\Repository\ProductService\Data;


class ProductServiceCategory
{
    public $id;
    public $name;
    public $description;

    public function __construct($data)
    {
        $this->id = $data->id;
        $this->name = $data->name;
        $this->description = $data->description;
    }
}