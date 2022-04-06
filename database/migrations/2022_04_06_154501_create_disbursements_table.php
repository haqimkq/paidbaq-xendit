<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disbursements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('disbursementId', 100)->nullable();
            $table->string('external_id', 100);
            $table->string('user_id', 100)->nullable();
            $table->integer('amount');
            $table->string('bank_code', 100);
            $table->string('account_holder_name', 100);
            $table->string('account_number', 100);
            $table->text('description');
            $table->string('status', 100)->default("PENDING");
            $table->string('email_to', 100)->nullable();
            $table->string('email_cc', 100)->nullable();
            $table->string('email_bcc', 100)->nullable();
            $table->text('failure_code', 100)->nullable();
            $table->boolean('is_instant', 100)->nullable()->default(false);
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
        Schema::dropIfExists('disbursements');
    }
}
