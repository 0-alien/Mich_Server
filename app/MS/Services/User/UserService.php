<?php

namespace App\MS\Services\User;

use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation;
use App\MS\Models\Token;
use App\MS\Models\User\User;

class UserService {

  public static function get($payload) {
    Validation::validate($payload, Validation::getUser());

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