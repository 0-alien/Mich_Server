<?php

namespace App\MS\Helpers;

class FCM {

  public static function send($to, $title, $body, $data) {
    $msg = array (
      'title'	=> $title,
      'body' => $body
    );
    $fields = array (
      'to' => $to,
      'notification' => $msg,
      'data' => $data,
      'sound' => 'default',
      'badge' => 1
    );
    $headers = array (
      'Authorization: key=AAAAoybEsDQ:APA91bGpARFjr6BneFv55N-YzoBOEOvJTjLGNbqH9oAxQGr8VipSEd0_7BUJWbz1kW3QaA7h9cgzL_g9cS2kANb-PsDsZu8Evz0sTiRNC-kHaxMMgXysUmrNG5fzbavk0zPcYByUb0GA',
      'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    curl_exec($ch );
    curl_close( $ch );
  }

}