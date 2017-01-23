<?php

namespace App\MS;

class Responder {

  public static function respond($code, $message, $data = null) {
    $response = [
      'code' => $code,
      'message' => '[' . StatusCodes::$MESSAGE[$code] . '] ' . $message
    ];

    if (!is_null($data) && $code === StatusCodes::SUCCESS) {
      $response['data'] = $data;
    }

    return response()->json($response);
  }

}