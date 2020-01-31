<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTimbanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_timbangans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_ticket')->unique();
            $table->dateTime('tanggal_masuk');
            $table->string('no_kendaraan');
            $table->string('pelanggan');
            $table->string('tandan');
            $table->string('1st_weight');
            $table->string('2nd_weight');
            $table->string('netto_weight');
            $table->string('potongan_gradding');
            $table->string('setelah_gradding');
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
        Schema::dropIfExists('data_timbangans');
    }
}
