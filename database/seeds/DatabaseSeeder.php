<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

  public function run() {
    for ($i = 1; $i <= 50; $i++) {
      DB::table('credentials')->insert([
        'email' => str_random(10).'@gmail.com',
        'password' => '866b9f7bc56964632eda158e9023504f699ce7a99bf4b6bbbb717f5481aebef2',
        'salt' => '11949251205864de933c44b5.79888714',
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
      ]);


      DB::table('users')->insert([
        'firstname' => str_random(10),
        'lastname' => str_random(10),
        'following' => '[]',
        'followers' => '[]',
        'mich' => '[]',
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
      ]);


      DB::table('tokens')->insert([
        'token' => str_random(64),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
      ]);


      DB::table('posts')->insert([
        'user_id' => $i,
        'title' => str_random(50),
        'mich' => '[]',
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
      ]);
    }
  }

}