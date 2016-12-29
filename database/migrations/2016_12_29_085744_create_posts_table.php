<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

  public function up() {
    Schema::create('posts', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('user_id');
      $table->string('title');
      $table->string('image')->default('http://wallpaper-gallery.net/images/funny-pictures/funny-pictures-2.jpg');
      $table->mediumText('mich');
      $table->timestamps();

      $table->foreign('user_id')->references('id')->on('credentials')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('posts');
  }

}