<?php

namespace App\MS\Services\FCM;

use App\MS\Models\Token;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

class FCMService {

  public static function update($payload) {
    V::validate($payload, array_merge(V::fcmrt));

    $token = Token::where('token', $payload['token'])->first();

    $token->fcmrt = $payload['fcmrt'];

    $token->save();

    return Responder::respond(StatusCodes::SUCCESS, 'FCM Registration Token Updated');
  }

}