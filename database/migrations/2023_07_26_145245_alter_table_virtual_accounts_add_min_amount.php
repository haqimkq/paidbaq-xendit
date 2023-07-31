<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableVirtualAccountsAddMinAmount extends Migration
{
    public function up()
    {
        Schema::table('virtual_accounts', function (Blueprint $table) {
            $table->integer("min_amount")->default(0)->after("expected_amount");
        });
    }

    public function down()
    {
        Schema::table('virtual_accounts', function (Blueprint $table) {
            $table->dropColumn("min_amount");
        });
    }
}
