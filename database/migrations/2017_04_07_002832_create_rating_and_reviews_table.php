<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingAndReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating_and_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('review');
            $table->integer('rating');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rating_and_reviews');
    }
}
