<?php


Route::get('push', function () {

  $options = ['cluster' => 'eu', 'encrypted' => true];
  $pusher = new Pusher(env('PUSHER_KEY'), env('PUSHER_SECRET'), env('PUSHER_APP_ID'), $options);
  $pusher->trigger('my-channel', 'my-event', 'hello mich');

});



Route::get('/', function () {
  return view('pusher');
});