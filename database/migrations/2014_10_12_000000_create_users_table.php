<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 60);
            $table->string('email', 60)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',60);
            $table->string('jenis_user', 10);
            $table->string('telp', 15);
            $table->string('alamat', 60);
            $table->string('foto', 30);
            $table->string('latitude',15);
            $table->string('longitude',15);
            $table->string('latitude_favorite',15);
            $table->string('longitude_favorite',15);
            $table->string('status', 10)->default('open');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}