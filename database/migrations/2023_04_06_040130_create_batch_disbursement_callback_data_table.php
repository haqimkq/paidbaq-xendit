<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchDisbursementCallbackDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_disbursement_callback_data', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('batch_disbursement_callback_id', 64)->nullable();
            $table->string('created', 45)->nullable();
            $table->string('updated', 45)->nullable();
            $table->string('external_id', 36)->nullable();
            $table->float('amount', 18,2)->nullable();
            $table->string('bank_code', 32)->nullable();
            $table->string('bank_account_number', 32)->nullable();
            $table->string('bank_account_name', 150)->nullable();
            $table->text('description')->nullable();
            $table->text('email_to')->nullable();
            $table->string('status', 45)->nullable();
            $table->string('valid_name', 100)->nullable();
            $table->string('bank_reference', 100)->nullable();
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
        Schema::dropIfExists('batch_disbursement_callback_data');
    }
}
