<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeerulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feerules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('feeruleId', 100)->nullable();
            $table->string('name', 100);
            $table->text('description');
            $table->string('routes', 255);
            $table->string('metadata', 100)->nullable();
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
        Schema::dropIfExists('feerules');
    }
}
