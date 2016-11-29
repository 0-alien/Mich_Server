<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCredentialsTable extends Migration
{

  public function up()
  {
    Schema::create('credentials', function (Blueprint $table)
    {
      $table->increments('id');
      $table->string('email');
      $table->char('password', 40);
      $table->char('salt', 32);
      $table->timestamps();
    });
  }



  public function down()
  {
    Schema::drop('credentials');
  }

}