<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

  public function run() {
    for ($i = 1; $i <= 50; $i++) {
      DB::table('credentials')->insert([
        'username' => str_random(10),
        'email' => str_random(10).'@gmail.com',
        'password' => '$2y$10$mYokVfr2sVTisG5fzgRVMuANTUuxYype6NapC8t5v477W26GVanoK',
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
      ]);


      DB::table('users')->insert([
        'firstname' => str_random(10),
        'lastname' => str_random(10),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
      ]);
    }
  }

}