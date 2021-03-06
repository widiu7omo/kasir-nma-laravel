<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataKwitansisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_kwitansis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_berkas')->unique();
            $table->string('tanggal_pembayaran');
            $table->string('no_pembayaran')->unique();
            $table->string('no_spb')->unique();
            $table->string('nama_supir');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('data_timbangan_id');
            $table->unsignedBigInteger('master_harga_id');
            $table->unsignedBigInteger('data_spb_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('data_timbangan_id')->references('id')->on('data_timbangans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('master_harga_id')->references('id')->on('master_hargas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('data_spb_id')->references('id')->on('data_spbs')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('data_kwitansis');
    }
}
