<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/11/17
 * Time: 8:35 PM
 */

namespace App\Repository\ProductService;


interface ProductServiceInterface
{
    public function getAll();

    public function create($data, $images);

    public function update($id, $data);

    public function delete($id);

    public function detail($id);

    public function createCategory($data);

    public function updateCategory($slug, $data);

    public function deleteCategory($slug);

}