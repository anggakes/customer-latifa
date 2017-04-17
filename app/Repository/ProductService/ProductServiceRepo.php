<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/11/17
 * Time: 8:35 PM
 */

namespace App\Repository\ProductService;


use App\Models\Category;
use App\Models\ProductService;
use App\Repository\ProductService\Data\ProductServiceData;

class ProductServiceRepo implements ProductServiceInterface
{
    public $productServices;

    public function __construct()
    {

    }


    public function getAll($offset = 0, $limit = 10)
    {
        $services = ProductService::with('categories')->limit($limit)->offset($offset)->get();
        $newFormat = [];
        foreach ($services as $service){
            $newFormat[] = new ProductServiceData($service);
        }
        return $newFormat;
    }

    public function create($data, $images)
    {
        $product = ProductService::create($data);

        if($data['category']){
            $product->categories()->attach($data['category']);
        }

        foreach (request()->file('images') as $image){
            $product->addMedia($image)->toMediaCollection('service_product');
        }


        return $this->detail($product->service_id);
    }

    public function update($id, $data)
    {
        $product = ProductService::where('service_id', $id)->update($data);
        if($data['category']){
            $product->categories()->detach();
            $product->categories()->attach($data['category']);
        }

        return $this->detail($product->service_id);
    }

    public function delete($id)
    {
        return ProductService::where('service_id', $id)->delete();
    }

    public function detail($id)
    {
        return ProductService::where('service_id', $id)->with('categories')->get();
    }

    public function createCategory($data)
    {
        return Category::create($data);
    }

    public function updateCategory($slug, $data)
    {
        return Category::where('slug', $slug)->update($data);
    }

    public function deleteCategory($slug)
    {
        return Category::where('slug', $slug)->delete();
    }
}