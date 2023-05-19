<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchDisbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_disbursements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('x_idempotency_key', 64)->nullable();
            $table->string('for_user_id', 64)->nullable();
            $table->string('reference', 64);
            $table->string('status', 64)->nullable()->comment("UPLOADING");
            $table->string('request_status', 64)->default("CREATED")->comment("status internal:  CREATED => dibuat, SUCCESS => berhasil, FAILED => gagal");
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
        Schema::dropIfExists('batch_disbursements');
    }
}
