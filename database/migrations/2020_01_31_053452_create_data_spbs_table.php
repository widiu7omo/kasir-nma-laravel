<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataSpbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_spbs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('master_spb_id');
            $table->unsignedBigInteger('master_korlap_id');
            $table->foreign('master_spb_id')->references('id')->on('master_spb')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('master_korlap_id')->references('id')->on('master_korlap')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('data_spbs');
    }
}
