<?php

namespace App\MS\Services\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\MS\Models\Token;
use App\MS\Models\User\Credential;
use App\MS\Models\User\User;
use App\MS\Responder;
use App\MS\Services\Security\Hash;
use App\MS\StatusCodes;

class AuthService {

  public static function register(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'username' => 'required|alpha_num|max:50',
      'email' => 'required|email',
      'password' => 'required|min:6|max:50',
      'firstname' => 'required|alpha|min:2|max:50',
      'lastname' => 'required|alpha|min:2|max:50'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }


    if (Credential::where('username', $payload->username)->exists()) {
      return Responder::respond(StatusCodes::ALREADY_EXISTS, 'Account with this username already exists');
    }

    if (Credential::where('email', $payload->email)->exists()) {
      return Responder::respond(StatusCodes::ALREADY_EXISTS, 'Account with this email already exists');
    }


    $credential = new Credential();
    $credential->username = $payload->username;
    $credential->email = $payload->email;
    $credential->salt = Hash::salt();
    $credential->password = Hash::make($payload->password, $credential->salt);
    $credential->save();

    $user = new User();
    $user->id = $credential->id;
    $user->firstname = $payload->firstname;
    $user->lastname = $payload->lastname;
    $user->following = '[]';
    $user->followers = '[]';
    $user->mich = '[]';
    $user->save();


    return Responder::respond(StatusCodes::SUCCESS, 'Account created successfully');
  }



  public static function login($payload) {
    $usernameType = (filter_var($payload->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username');

    if (!Credential::where($usernameType, $payload->username)->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Account with this '. $usernameType .' could not be found');
    }

    $credential = Credential::where($usernameType, $payload->username)->first();

    if (Hash::make($payload->password, $credential->salt) !== $credential->password) {
      return Responder::respond(StatusCodes::INVALID_PARAMETER, 'Invalid password');
    }


    $token = null;

    if (Token::where('id', $credential->id)->exists()) {
      $token = Token::where('id', $credential->id)->first();
    } else {
      $token = new Token();
      $token->id = $credential->id;
      $token->token = Hash::unique();
      $token->save();
    }

    return Responder::respond(StatusCodes::SUCCESS, 'Logged in successfully', ['token' => $token->token]);
  }

}