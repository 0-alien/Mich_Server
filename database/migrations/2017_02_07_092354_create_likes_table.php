<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration {

  public function up() {
    Schema::create('likes', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('userid')->unsigned();
      $table->integer('postid')->unsigned();
      $table->timestamps();

      $table->foreign('userid')->references('id')->on('credentials')->onDelete('cascade');
      $table->foreign('postid')->references('id')->on('posts')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('likes');
  }

}