<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingAndReview extends Model
{
    //

    protected $fillable = [
        'user_id',
        'invoice_number',
        'review',
        'rating'
    ];
}
