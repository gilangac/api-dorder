<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->dateTime('waktu_pesan');
            $table->dateTime('waktu_proses');
            $table->dateTime('waktu_kirim');
            $table->dateTime('waktu_selesai');
            $table->integer('total_harga');
            $table->integer('ongkir');
            $table->integer('total_produk');
            $table->string('alamat_antar');
            $table->string('latitude_antar');
            $table->string('longitude_antar');
            $table->tinyInteger('rating');
            $table->timestamps();
        });        
        Schema::table('pesanans', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pesanans');
        Schema::dropIfExists('detail_pesanans');
    }
}