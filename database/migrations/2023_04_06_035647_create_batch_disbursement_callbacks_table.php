<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchDisbursementCallbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_disbursement_callbacks', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('created', 45)->nullable();
            $table->string('updated', 45)->nullable();
            $table->string('reference', 36)->nullable();
            $table->string('user_id', 45)->nullable();
            $table->integer('total_uploaded_count')->unsigned()->default(0);
            $table->float('total_uploaded_amount', 18,2)->default(0);
            $table->string('approved_at', 45)->nullable();
            $table->string('approver_id', 45)->nullable();
            $table->string('status', 45)->nullable();
            $table->integer('total_disbursed_count')->unsigned()->default(0);
            $table->float('total_disbursed_amount', 18, 2)->default(0);
            $table->integer('total_error_count')->unsigned()->default(0);
            $table->integer('total_error_amount')->unsigned()->default(0);
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
        Schema::dropIfExists('batch_disbursement_callbacks');
    }
}
