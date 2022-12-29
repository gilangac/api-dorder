<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->integer('id_kategori_produk')->unsigned();
            // $table->foreign('user_id')->references('id')->on('users');
            $table->string('nama_produk',60);
            $table->integer('harga');
            $table->integer('stok');
            $table->string('deskripsi', 255);
            
            $table->string('foto', 30);
            $table->timestamps();
        });

        Schema::table('produks', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_kategori_produk')->references('id')->on('kategori_produks')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
}
