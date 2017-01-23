<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

  public function up() {
    Schema::create('users', function (Blueprint $table)  {
      $table->increments('id');
      $table->string('firstname');
      $table->string('lastname');
      $table->string('avatar')->default('https://s23.postimg.org/ruz8mjsfv/funny_avatar_by_avatarys_cartoon_avatar_by_avata.jpg');
      $table->timestamps();

      $table->foreign('id')->references('id')->on('credentials')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('users');
  }

}