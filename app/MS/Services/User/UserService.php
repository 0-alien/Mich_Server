<?php

namespace App\MS\Services\User;

use App\MS\StatusCodes;
use App\MS\Responder;
use App\MS\Validation as V;
use App\MS\Models\Token;
use App\MS\Models\User\User;

class UserService {

  private static function Vget($payload) {
    $userID = V::userID;
    $userID['userID'] .= '|exists:credentials,id';

    V::validate($payload, $userID);
  }


  public static function get($payload) {
    self::Vget($payload);

    $token = Token::where('token', $payload['token'])->first();

    $userID = (isset($payload['userID']) ? $payload['userID'] : $token->id);
    $user = User::where('id', $userID)->first();

    $profile = [
      'id' => $userID,
      'firstname' => $user->firstname,
      'lastname' => $user->lastname,
      'username' => $user->credential->username,
      'email' => $user->credential->email,
      'avatar' => $user->avatar
    ];

    return Responder::respond(StatusCodes::SUCCESS, '', $profile);
  }

}