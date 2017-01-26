<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

  public function up() {
    Schema::create('posts', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('userid')->unsigned();
      $table->string('title', 200);
      $table->string('image');
      $table->timestamps();

      $table->foreign('userid')->references('id')->on('credentials')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('posts');
  }

}