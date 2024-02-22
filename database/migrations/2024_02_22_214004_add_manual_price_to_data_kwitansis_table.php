<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManualPriceToDataKwitansisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_kwitansis', function (Blueprint $table) {
            $table->string('custom_price')->nullable();
            $table->boolean('is_custom_price')->default(false);
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
            $table->dropColumn('custom_price');
            $table->dropColumn('is_custom_price');
        });
    }
}
