<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTransferConfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transfer_confirmations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('invoice_number');
            $table->string('destination_bank');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('customer_bank');
            $table->string('customer_account_number');
            $table->string('customer_account_name');
            $table->boolean('confirmed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_transfer_confirmations');
    }
}
