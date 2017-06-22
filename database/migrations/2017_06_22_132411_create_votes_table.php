<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration {

  public function up() {
    Schema::create('votes', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('battle')->unsigned();
      $table->tinyInteger('host')->unsigned();
      $table->timestamps();

      $table->foreign('battle')->references('id')->on('battles')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('votes');
  }

}