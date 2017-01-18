<?php

namespace App\MS\Services\User;

use App\MS\Models\Token;
use App\MS\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\MS\Responder;
use App\MS\StatusCodes;

class UserService {

  public static function get(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'userID' => 'numeric|exists:credentials,id'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }

    $token = Token::where('token', $payload->token)->first();

    $userID = (isset($payload->userID) ? $payload->userID : $token->id);
    $user = User::where('id', $userID)->first();

    $profile = [
      'firstname' => $user->firstname,
      'lastname' => $user->lastname,
      'username' => $user->credential->username,
      'email' => $user->credential->email,
      'avatar' => $user->avatar
    ];

    return Responder::respond(StatusCodes::SUCCESS, '', $profile);
  }



  public static function update(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'firstname' => 'required|alpha|min:2|max:50',
      'lastname' => 'required|alpha|min:2|max:50'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }

    $token = Token::where('token', $payload->token)->first();

    $user = $token->credential->user;

    $user->firstname = $payload->firstname;
    $user->lastname = $payload->lastname;
    $user->save();

    return Responder::respond(StatusCodes::SUCCESS, 'Profile updated successfully', $user);
  }



  public static function delete(Request $request) {
    $payload = json_decode($request->payload);

    $token = Token::where('token', $payload->token)->first();

    $credential = $token->credential;

    $credential->delete();

    return Responder::respond(StatusCodes::SUCCESS, 'Profile deleted successfully');
  }

}