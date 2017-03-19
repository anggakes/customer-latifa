<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    //

    protected $fillable = ['user_id', 'nominal', 'type', 'desc'];

    protected $attributes = [
        'desc' => ''
    ];
}
