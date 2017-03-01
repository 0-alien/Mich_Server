<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

  public function run() {
//    DB::table('credentials')->insert([
//      'username' => 'alien',
//      'email' => 'tskhondia.giorgi@gmail.com',
//      'password' => '$2y$10$mYokVfr2sVTisG5fzgRVMuANTUuxYype6NapC8t5v477W26GVanoK',
//      'created_at' => \Carbon\Carbon::now(),
//      'updated_at' => \Carbon\Carbon::now()
//    ]);
//
//    DB::table('users')->insert([
//      'name' => 'Giorgi Tskhondia',
//      'created_at' => \Carbon\Carbon::now(),
//      'updated_at' => \Carbon\Carbon::now()
//    ]);


    for ($i = 1; $i < 50; $i++) {
//      DB::table('credentials')->insert([
//        'username' => str_random(10),
//        'email' => str_random(10).'@gmail.com',
//        'password' => '$2y$10$mYokVfr2sVTisG5fzgRVMuANTUuxYype6NapC8t5v477W26GVanoK',
//        'created_at' => \Carbon\Carbon::now(),
//        'updated_at' => \Carbon\Carbon::now()
//      ]);
//
//
//      DB::table('users')->insert([
//        'name' => str_random(10) . ' ' . str_random(10),
//        'created_at' => \Carbon\Carbon::now(),
//        'updated_at' => \Carbon\Carbon::now()
//      ]);
//
//
//      DB::table('posts')->insert([
//        'userid' => $i,
//        'title' => str_random(10),
//        'image' => 'noavatar',
//        'created_at' => \Carbon\Carbon::now(),
//        'updated_at' => \Carbon\Carbon::now()
//      ]);

      for ($j = 1; $j <= 10; $j++) {
        DB::table('comments')->insert([
          'userid' => $j,
          'postid' => $i,
          'data' => 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old',
          'created_at' => \Carbon\Carbon::now(),
          'updated_at' => \Carbon\Carbon::now()
        ]);
      }
    }
  }

}