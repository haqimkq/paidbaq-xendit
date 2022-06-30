<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApiClientsTableRev1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_clients', function (Blueprint $table) {
            
            $table->text("cb_fva_paid")->nullable()->after("status");
            $table->text("cb_fva_created")->nullable()->after("cb_fva_paid");
            $table->text("cb_disbursement_sent")->nullable()->after("cb_fva_created");
            $table->text("cb_batch_disbursement_sent")->nullable()->after("cb_disbursement_sent");
            $table->text("cb_account_created")->nullable()->after("cb_batch_disbursement_sent");
            $table->text("cb_account_updated")->nullable()->after("cb_account_created");

          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
