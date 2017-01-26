<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecoveryTable extends Migration {

  public function up() {
    Schema::create('recovery', function (Blueprint $table) {
      $table->increments('id');
      $table->char('code', 6);
      $table->char('token', 64)->unique();
      $table->integer('tries')->default(0);
      $table->tinyInteger('match')->default(0);
      $table->timestamps();

      $table->foreign('id')->references('id')->on('credentials')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('recovery');
  }

}