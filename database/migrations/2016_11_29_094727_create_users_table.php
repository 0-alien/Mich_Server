<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

  public function up() {
    Schema::create('users', function (Blueprint $table)  {
      $table->increments('id');
      $table->string('name');
      $table->datetime('dateofbirth');
      $table->string('location');
      $table->string('avatar')->default('noavatar');
      $table->integer('win')->unsigned(0);
      $table->integer('draw')->unsigned(0);
      $table->integer('loss')->unsigned(0);
      $table->timestamps();

      $table->foreign('id')->references('id')->on('credentials')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('users');
  }

}