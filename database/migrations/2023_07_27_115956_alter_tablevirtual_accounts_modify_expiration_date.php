<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablevirtualAccountsModifyExpirationDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("virtual_accounts", function($table) {
            $table->string("expiration_date", 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("virtual_accounts", function($table) {
            $table->string("expiration_date", 50)->change();
        });
    }
}
