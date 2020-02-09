<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalPaymentColumnInDataKwitansisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_kwitansis', function (Blueprint $table) {
            //
            $table->string('total_harga')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_kwitansis', function (Blueprint $table) {
            //
            $table->dropColumn('total_harga');
        });
    }
}
