<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXenplatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xenplatforms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('accountID', 100)->nullable();
            $table->string('type', 100);
            $table->string('email', 100);
            $table->string('business_name', 100);
            $table->string('country', 100)->nullable();
            $table->string('status', 100)->default("Invited");
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
        Schema::dropIfExists('xenplatforms');
    }
}
