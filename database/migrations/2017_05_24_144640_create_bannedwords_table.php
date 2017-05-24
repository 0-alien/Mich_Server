<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannedwordsTable extends Migration {

    public function up() {
      Schema::create('bannedwords', function (Blueprint $table) {
        $table->increments('id');
        $table->string('word', 200);
        $table->timestamps();
      });
    }


    public function down() {
      Schema::drop('bannedwords');
    }
}