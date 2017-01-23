<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCredentialsTable extends Migration {

  public function up() {
    Schema::create('credentials', function (Blueprint $table) {
      $table->increments('id');
      $table->string('username', 50)->unique();
      $table->string('email', 100)->unique();
      $table->char('password', 60);
      $table->timestamps();
    });
  }



  public function down() {
    Schema::drop('credentials');
  }

}