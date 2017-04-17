<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 4/17/17
 * Time: 9:04 PM
 */

namespace App\Http\Controllers\Banner;


use App\Models\Banner;

class BannerController
{

    public function store(){

        $banner = Banner::create([
            "start" => request()->has('start') ? request('start') : null,
            "end" => request()->has('end') ? request('end') : null,
            "url"   => request('url'),
            "label" => request('label')
        ]);

        $banner->addMedia(request()->file('image'))->toMediaCollection('banners');

        return $banner;

    }

}