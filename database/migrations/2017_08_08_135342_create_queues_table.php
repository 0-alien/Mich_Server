<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueuesTable extends Migration {

  public function up() {
    Schema::create('queues', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('host')->unsigned();
      $table->integer('guest')->unsigned()->nullable();
      $table->timestamps();

      $table->foreign('host')->references('id')->on('credentials')->onDelete('cascade');
      $table->foreign('guest')->references('id')->on('credentials')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('queues');
  }

}