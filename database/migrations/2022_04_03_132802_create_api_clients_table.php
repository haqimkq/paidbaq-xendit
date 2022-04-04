<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("client_id", 128)->unique();
            $table->string("client_secret", 128);
            $table->string("signature_key", 191);
            $table->integer("owner")->unsigned()->default(0);
            $table->string("status", 15)->default("inactive");
            $table->string("environment", 50);
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
        Schema::dropIfExists('api_clients');
    }
}
