<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Services\Auth\AuthService;

class AuthController extends Controller {

  public function register(Request $request) {
    return AuthService::register($request);
  }



  public function login(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'type' => 'required|numeric|in:0,1,2',
      'email' => 'required|email',
      'password' => 'required|min:6|max:50'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }


    if ($payload->type === 0) {
      return AuthService::login($payload);
    }
  }

}