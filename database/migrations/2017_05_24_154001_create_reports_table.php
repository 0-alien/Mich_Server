<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration {

  public function up() {
    Schema::create('reports', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('userid')->unsigned();
      $table->tinyInteger('type');
      $table->integer('item');
      $table->timestamps();

      $table->foreign('userid')->references('id')->on('credentials')->onDelete('cascade');
    });
  }


  public function down() {
    Schema::drop('reports');
  }

}