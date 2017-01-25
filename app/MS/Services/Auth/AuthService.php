<?php

namespace App\MS\Services\Auth;

use Illuminate\Support\Facades\Hash;

use App\MS\StatusCodes;
use App\MS\Responder;
use App\MS\Validation as V;
use App\MS\Models\User\Credential;
use App\MS\Models\User\User;
use App\MS\Models\Token;

class AuthService {

  private static function Vregister($payload) {
    $username = V::username;
    $username['username'] .= '|not_exists:credentials,username';

    $email = V::email;
    $email['email'] .= '|not_exists:credentials,email';

    V::validate($payload, array_merge($username, $email, V::password, V::firstname, V::lastname));
  }


  public static function register($payload) {
    self::Vregister($payload);

    $credential = new Credential();
    $credential->username = $payload['username'];
    $credential->email = $payload['email'];
    $credential->password = Hash::make($payload['password']);
    $credential->save();

    $user = new User();
    $user->id = $credential->id;
    $user->firstname = $payload['firstname'];
    $user->lastname = $payload['lastname'];
    $user->save();

    return Responder::respond(StatusCodes::SUCCESS, 'Account created');
  }



  private static function generateUniqueToken() {
    $token = str_random(64);

    while (Token::where('token', $token)->exists()) {
      $token = str_random(64);
    }

    return $token;
  }


  public static function login($payload) {
    $credentialType = (filter_var($payload['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username');

    if (!Credential::where($credentialType, $payload['username'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Account with this ' . $credentialType . ' could not be found');
    }


    $credential = Credential::where($credentialType, $payload['username'])->first();

    if (!Hash::check($payload['password'], $credential->password)) {
      return Responder::respond(StatusCodes::INVALID_CREDENTIALS, 'Invalid credentials');
    }


    $token = null;

    if (Token::where('id', $credential->id)->exists()) {
      $token = Token::where('id', $credential->id)->first();
    } else {
      $token = new Token();
      $token->id = $credential->id;
      $token->token = self::generateUniqueToken();
      $token->save();
    }

    return Responder::respond(StatusCodes::SUCCESS, 'Logged in', ['token' => $token->token]);
  }



  public static function logout($payload) {
    $token = Token::where('token', $payload['token'])->first();

    $token->delete();

    return Responder::respond(StatusCodes::SUCCESS, 'Logged out');
  }

}