<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUniqueValueForNikKwitansiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('data_petanis', function (Blueprint $table) {
            $table->dropUnique('data_petanis_nik_unique');
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
        Schema::table('data_petanis', function (Blueprint $table) {
            $table->change('nik');
            $table->string('nik')->unique()->change();
        });
    }
}
