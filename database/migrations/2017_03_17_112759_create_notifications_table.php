<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

  public function up() {
    Schema::create('notifications', function (Blueprint $table)  {
      $table->increments('id');
      $table->integer('type')->unsigned();
      $table->string('message');
      $table->string('avatar');
      $table->boolean('status')->default(0);
      $table->integer('userid')->unsigned();
      $table->integer('postid')->unsigned()->default(0);
      $table->integer('commentid')->unsigned()->default(0);
      $table->integer('followerid')->unsigned()->default(0);
      $table->integer('battleid')->unsigned()->default(0);
      $table->timestamps();

      $table->foreign('userid')->references('id')->on('credentials')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('notifications');
  }

}