<?php

namespace App\MS\Helpers;

class Mail {

  public static function send($view, $parameters, $to, $toName, $subject) {
    $mailParameters = [
      'to' => $to,
      'toName' => $toName,
      'subject' => $subject
    ];


    \Mail::send('mail.'.$view, $parameters, function($message) use ($mailParameters) {
      $message->to($mailParameters['to'], $mailParameters['toName'])->subject($mailParameters['subject']);
      $message->from('michserver0@gmail.com', 'Mich Support');
    });
  }

}