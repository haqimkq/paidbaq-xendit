<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchDisbursementDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_disbursement_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('batch_disbursement_id');
            $table->float('amount', 18,2);
            $table->string('bank_code', 32);
            $table->string('bank_account_number', 32);
            $table->string('bank_account_name', 150);
            $table->text('description')->nullable();
            $table->string('external_id', 64)->nullable();
            $table->longText('email_to')->nullable();
            $table->longText('email_cc')->nullable();
            $table->longText('email_bcc')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_disbursement_data');
    }
}
