<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokensTable extends Migration {

  public function up() {
    Schema::create('tokens', function (Blueprint $table) {
      $table->increments('id');
      $table->char('token', 64)->unique();
      $table->timestamps();

      $table->foreign('id')->references('id')->on('credentials')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('tokens');
  }
}