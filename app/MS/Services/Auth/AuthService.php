<?php

namespace App\MS\Services\Auth;

use App\MS\Models\Token;
use App\MS\Models\User\Credential;
use App\MS\Models\User\User;
use App\MS\Responder;
use App\MS\Services\Security\Hash;
use App\MS\StatusCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthService {

  public static function register(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'email' => 'required|email',
      'password' => 'required|min:6|max:50',
      'firstname' => 'required|alpha|min:2|max:50',
      'lastname' => 'required|alpha|min:2|max:50'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }


    if (Credential::where('email', $payload->email)->exists()) {
      return Responder::respond(StatusCodes::ALREADY_EXISTS, 'Account with this email already exists');
    }


    $credential = new Credential();
    $credential->email = $payload->email;
    $credential->salt = Hash::salt();
    $credential->password = Hash::make($payload->password, $credential->salt);
    $credential->save();

    $user = new User();
    $user->id = $credential->id;
    $user->firstname = $payload->firstname;
    $user->lastname = $payload->lastname;
    $user->save();


    return Responder::respond(StatusCodes::SUCCESS, 'Account created successfully');
  }



  public static function login($payload) {
    if (!Credential::where('email', $payload->email)->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Account with this email could not be found');
    }

    $credential = Credential::where('email', $payload->email)->first();

    if (Hash::make($payload->password, $credential->salt) !== $credential->password) {
      return Responder::respond(StatusCodes::INVALID_PARAMETER, 'Invalid password');
    }


    $token = null;

    if (Token::exists($credential->id)) {
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