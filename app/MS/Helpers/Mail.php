<?php

namespace App\MS\Helpers;

class Mail {

  public static function send() {
    \Mail::send([], [], function($message) {
      $message->to('gtskh11@freeuni.edu.ge', 'Giorgi Tskhondia')->subject('Password Reset');
      $message->from('mich@gmail.com', 'Mich Support');
    });
  }

}