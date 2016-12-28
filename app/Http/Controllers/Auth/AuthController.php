<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\MS\Responder;
use App\MS\StatusCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\MS\Services\Auth\AuthService;

class AuthController extends Controller {

  /**
   *
   * @SWG\Post(
   *   path="/api/auth/register",
   *   summary="",
   *   tags={"Auth"},
   *   description="",
   *
   *
   *   @SWG\Parameter(
   *     in="body",
   *     name="payload",
   *     description="",
   *     required=true,
   *     default="{""email"": ""alien@post.com"", ""password"": ""mypass"", ""firstname"": ""giorgi"", ""lastname"": ""cxondia""}",
   *     @SWG\Schema(
   *       type="json",
   *       @SWG\Items()
   *     )
   *   ),
   *
   *
   *   @SWG\Response(
   *     response=200,
   *     description="successful operation",
   *     @SWG\Schema(ref="#/definitions/successModel")
   *   ),
   *   @SWG\Response(
   *     response="400",
   *     description="Invalid request parameters",
   *     @SWG\Schema(ref="#/definitions/errorModel")
   *   ),
   * )
   *
   *
   * @param Request $request
   * @return mixed
   */
  public function register(Request $request) {
    return AuthService::register($request);
  }



  public function authenticate(Request $request) {
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