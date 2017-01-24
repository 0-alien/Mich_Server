<?php

namespace App\MS\Services\Auth;

use Illuminate\Support\Facades\Hash;

use App\MS\Models\Recovery;
use App\MS\StatusCodes;
use App\MS\Responder;
use App\MS\Validation as V;
use App\MS\Helpers\Mail;
use App\MS\Models\User\Credential;

class RecoveryService {

  private static function generateCode() {
    $code = '';

    for ($i = 0; $i < 6; $i++) {
      $code .= rand(0, 9);
    }

    return $code;
  }


  public static function sendRecovery($payload) {
//    V::validate($payload, V::mixed);
//
//    $credentialType = (filter_var($payload['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username');
//
//    if (!Credential::where($credentialType, $payload['username'])->exists()) {
//      return Responder::respond(StatusCodes::NOT_FOUND, 'Account with this ' . $credentialType . ' could not be found');
//    }
//
//
//    $credential = Credential::where($credentialType, $payload['username'])->first();
//
//    $recovery = new Recovery();
//    $recovery->id = $credential->id;
//    $recovery->code = self::generateCode();
//    $recovery->token = Hash::make($recovery->code);
//    $recovery->save();

    Mail::send();

    return Responder::respond(StatusCodes::SUCCESS, 'mail sent');
  }

}