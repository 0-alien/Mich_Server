<?php

namespace App\Http\Middleware;

use App\MS\Responder;
use App\MS\StatusCodes;

use Closure;

class JsonPayload {

  public function handle($request, Closure $next) {
    json_decode($request->payload);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return Responder::respond(StatusCodes::INVALID_PAYLOAD, 'Missing `payload` parameter');
    }


    return $next($request);
  }

}