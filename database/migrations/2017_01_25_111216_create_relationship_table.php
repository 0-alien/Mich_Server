<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipTable extends Migration {

  public function up() {
    Schema::create('relationship', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('follower')->unsigned();
      $table->integer('following')->unsigned();
      $table->timestamps();

      $table->foreign('follower')->references('id')->on('credentials')->onDelete('cascade');
      $table->foreign('following')->references('id')->on('credentials')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('relationship');
  }

}