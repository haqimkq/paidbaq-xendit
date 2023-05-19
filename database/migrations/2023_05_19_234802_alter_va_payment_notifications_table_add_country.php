<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVaPaymentNotificationsTableAddCountry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('va_payment_notifications', function (Blueprint $table) {
            $table->string("country", 20)->after("transaction_timestamp_formatted")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('va_payment_notifications', function (Blueprint $table) {
            $table->dropColumn("country");
        });
    }
}
