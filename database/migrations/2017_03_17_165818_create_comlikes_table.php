<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComlikesTable extends Migration {

  public function up() {
    Schema::create('comlikes', function (Blueprint $table)  {
      $table->increments('id');
      $table->integer('userid')->unsigned();
      $table->integer('commentid')->unsigned();
      $table->timestamps();

      $table->foreign('userid')->references('id')->on('credentials')->onDelete('cascade');
      $table->foreign('commentid')->references('id')->on('comments')->onDelete('cascade');
    });
  }



  public function down() {
    Schema::drop('comlikes');
  }

}
