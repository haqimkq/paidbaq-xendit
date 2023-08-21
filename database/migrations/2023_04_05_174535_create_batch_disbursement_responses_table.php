<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchDisbursementResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_disbursement_responses', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->bigInteger('batch_disbursement_id');
            $table->string('created', 36);
            $table->string('reference', 36);
            $table->float('total_uploaded_amount', 18, 2)->default(0);
            $table->integer('total_uploaded_count')->unsigned()->default(0);
            $table->string('status', 64)->nullable()->comment("UPLOADING");
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
        Schema::dropIfExists('batch_disbursement_responses');
    }
}
