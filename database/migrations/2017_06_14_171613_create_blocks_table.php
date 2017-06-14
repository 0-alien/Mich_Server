<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlocksTable extends Migration {

  public function up() {
    Schema::create('block', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('userid')->unsigned();
      $table->integer('blockid')->unsigned();
      $table->timestamps();

      $table->foreign('userid')->references('id')->on('credentials')->onDelete('cascade');
      $table->foreign('blockid')->references('id')->on('credentials')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('block');
  }

}