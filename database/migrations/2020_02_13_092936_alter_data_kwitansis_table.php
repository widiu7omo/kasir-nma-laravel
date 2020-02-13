<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDataKwitansisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('data_kwitansis',function(Blueprint $table){
           $table->dropColumn('nama_supir');
           $table->unsignedBigInteger('data_petani_id');
           $table->foreign('data_petani_id')->references('id')->on('data_petanis')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('data_kwitansis',function(Blueprint $table){
           $table->string('nama_supir');
           $table->dropColumn('data_petani_id');
        });
    }
}
