<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirtualAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_id', 60)->nullable();
            $table->string('owner_id', 60)->nullable();
            $table->string("external_id", 100);
            $table->string("bank_code", 50);
            $table->string("name", 100);
            $table->boolean("is_closed")->default(0);
            $table->integer("expected_amount")->default(0);
            $table->string("virtual_account_number", 30);
            $table->string("account_number", 30)->nullable();
            $table->string("merchant_code", 10)->nullable();
            $table->string("currency", 20)->default("IDR");
            $table->string("status", 20)->nullable();
            $table->boolean("is_single_use")->default(0);
            $table->integer("suggested_amount")->default(0);
            $table->string("expiration_date", 50);
            $table->text("description")->nullable();
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
        Schema::dropIfExists('virtual_accounts');
    }
}
