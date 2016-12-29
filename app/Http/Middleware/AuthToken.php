<?php

namespace App\Http\Middleware;

use App\MS\Responder;
use App\MS\StatusCodes;

use Closure;

class AuthToken {

  public function handle($request, Closure $next) {
    $payload = json_decode($request->payload);

    if (!isset($payload->token)) {
      return Responder::respond(StatusCodes::TOKEN_MISSING, 'Missing `token` parameter');
    }


    if (!\App\MS\Models\Token::where('token', $payload->token)->exists()) {
      return Responder::respond(StatusCodes::INVALID_TOKEN, 'Invalid/Expired token');
    }


    return $next($request);
  }

}