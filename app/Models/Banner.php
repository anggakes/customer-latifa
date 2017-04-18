<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Banner extends Model implements HasMedia
{
    //
    use HasMediaTrait;

    protected $fillable = [
        "start","end",'url','label'
    ];

    public static function getActiveBanners($simple = true){
        $data = self::all();
        foreach ($data as $key=>$val){
            $banner = url($val->getMedia('banners')[0]->getUrl());
            $data[$key]->image = $banner;
            if($simple){
                unset($data[$key]->media);
                unset($data[$key]->start);
                unset($data[$key]->end);
                unset($data[$key]->created_at);
                unset($data[$key]->updated_at);
            }
        }

        return $data;
    }
}
