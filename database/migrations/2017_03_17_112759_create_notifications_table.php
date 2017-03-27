<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

  public function up() {
    Schema::create('notifications', function (Blueprint $table)  {
      $table->increments('id');
      $table->integer('type')->unsigned();
      $table->integer('itemid')->unsigned();
      $table->string('message');
      $table->boolean('status')->default(0);
      $table->integer('userid')->unsigned();
      $table->timestamps();

      $table->foreign('userid')->references('id')->on('credentials')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('notifications');
  }

}