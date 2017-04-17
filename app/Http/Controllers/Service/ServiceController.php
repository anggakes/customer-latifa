<?php

namespace App\Http\Controllers\Service;

use App\Models\ProductService;
use App\Repository\ProductService\ProductServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Validator;

class ServiceController extends Controller
{
    //

    public $service;

    public function __construct(ProductServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(){
        $page   = request('page') ?: 1;
        $limit  = request('limit') ?: 10;

        ProductService::$isConvertPoint = true;

        return response()->json($this->service->getAll(($page-1)*$limit,$limit));
    }

    public function store(){

        $data = request()->all();
        $validator = $this->validator($data);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->getMessageBag()->first());
        }

        return $this->service->create($data, request()->file('images'));

    }

    public function storeCategory(){
        $data = request()->all();
        $data['slug'] = str_slug($data['name']);
        $validator = Validator::make($data, [
           'slug'   => 'required|unique:categories'
        ]);
        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->getMessageBag()->first());
        }
        return $this->service->createCategory($data);
    }

    private function validator($data){
        return Validator::make($data, [
            'name'      => 'required|between:3,255',
            'regular_price'     => 'required|integer',
            'sale_price'     => 'required|integer',
            'service_id'    => 'required|between:3,255|unique:product_services',
        ]);
    }
}
