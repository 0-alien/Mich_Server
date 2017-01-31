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


  private static function generateUniqueToken() {
    $token = str_random(64);

    while (Recovery::where('token', $token)->exists()) {
      $token = str_random(64);
    }

    return $token;
  }


  public static function sendRecovery($payload) {
    V::validate($payload, V::mixed);

    $credentialType = (filter_var($payload['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username');

    if (!Credential::where($credentialType, $payload['username'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Account with this ' . $credentialType . ' could not be found');
    }


    $credential = Credential::where($credentialType, $payload['username'])->first();
    $user = $credential->user;

    Recovery::where('id', $credential->id)->delete();

    $recovery = new Recovery();
    $recovery->id = $credential->id;
    $recovery->code = self::generateCode();
    $recovery->token = self::generateUniqueToken();
    $recovery->save();

    Mail::send('recovery', ['code' => $recovery->code], $credential->email, $user->name, 'Password Recovery');

    return Responder::respond(StatusCodes::SUCCESS, 'Mail sent', ['token' => $recovery->token]);
  }



  public static function checkCode($payload) {
    V::validate($payload, array_merge(V::token, V::code));

    if (!Recovery::where('token', $payload['token'])->where('match', 0)->exists()) {
      return Responder::respond(StatusCodes::INVALID_TOKEN, '');
    }


    $recovery = Recovery::where('token', $payload['token'])->first();

    if ($payload['code'] != $recovery->code) {
      $recovery->tries += 1;
      $recovery->save();

      if ($recovery->tries === 3) {
        $recovery->delete();

        return Responder::respond(StatusCodes::TRY_LIMIT, 'Recovery code deleted');
      }

      return Responder::respond(StatusCodes::INVALID_PARAMETER, 'Invalid code');
    }


    $recovery->match = 1;
    $recovery->save();

    return Responder::respond(StatusCodes::SUCCESS, 'Code match', ['token' => $recovery->token]);
  }



  public static function recover($payload) {
    V::validate($payload, array_merge(V::token, V::password));

    if (!Recovery::where('token', $payload['token'])->where('match', 1)->exists()) {
      return Responder::respond(StatusCodes::INVALID_TOKEN, '');
    }


    $recovery = Recovery::where('token', $payload['token'])->where('match', 1)->first();

    $credential = $recovery->credential;
    $credential->password = Hash::make($payload['password']);
    $credential->save();

    return Responder::respond(StatusCodes::SUCCESS, 'Password changed');
  }

}