<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipTable extends Migration {

  public function up() {
    Schema::create('relationship', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('follower');
      $table->integer('following');
      $table->timestamps();
    });
  }



  public function down() {
    Schema::drop('relationship');
  }

}