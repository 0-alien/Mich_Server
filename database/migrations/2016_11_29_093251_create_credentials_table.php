<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCredentialsTable extends Migration {

  public function up() {
    Schema::create('credentials', function (Blueprint $table) {
      $table->increments('id');
      $table->string('username')->unique();
      $table->string('email')->unique();
      $table->char('password', 64);
      $table->char('salt', 50);
      $table->timestamps();
    });
  }



  public function down() {
    Schema::drop('credentials');
  }

}