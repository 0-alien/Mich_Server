<?php

class Logger {

  public function log($msg) {
    $myfile = fopen("log.txt", "a") or die("Unable to open file!");
    fwrite($myfile, $msg . PHP_EOL);
    fclose($myfile);
  }

}


Route::get('push', function () {

  $options = ['cluster' => 'eu', 'encrypted' => true];
  $pusher = new Pusher(env('PUSHER_KEY'), env('PUSHER_SECRET'), env('PUSHER_APP_ID'), $options);

  $pusher->set_logger(new Logger());

  $pusher->trigger('59', 'invitation', ['battle' => 19]);

});



Route::get('/', function () {

  return view('pusher');

});