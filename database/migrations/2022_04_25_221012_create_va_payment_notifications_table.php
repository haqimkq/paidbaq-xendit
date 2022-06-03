<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaPaymentNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('va_payment_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("payment_id", 150)->nullable();
            $table->string("external_id", 100)->nullable();
            $table->string("account_number", 30)->nullable();
            $table->string("virtual_account_id", 50)->nullable();
            $table->string("callback_virtual_account_id", 150)->nullable();
            $table->float("amount", 18,2)->nullable();
            $table->string("merchant_code", 10)->nullable();
            $table->string("bank_code", 50)->nullable();
            $table->string("transaction_timestamp", 50)->nullable();
            $table->dateTime("transaction_timestamp_formatted")->nullable();
            $table->string("currency", 20)->default("IDR")->nullable();
            $table->text("description")->nullable();
            $table->string("created", 50)->nullable();
            $table->string("updated", 50)->nullable();
            $table->string("owner_id", 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('va_payment_notifications');
    }
}
