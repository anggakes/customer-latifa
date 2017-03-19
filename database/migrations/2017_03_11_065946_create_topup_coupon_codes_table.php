<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopupCouponCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topup_coupon_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('code',255);
            $table->unsignedInteger('nominal');
            $table->timestamp('expired')->nullable();
            $table->unsignedInteger('used_by')->nullable();
            $table->timestamp('used_date')->nullable();
            $table->string('status',10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topup_coupon_codes');
    }
}
