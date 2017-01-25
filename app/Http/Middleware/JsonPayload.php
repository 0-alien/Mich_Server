<?php

namespace App\Http\Middleware;

use App\MS\Responder;
use App\MS\StatusCodes;

use Closure;

class JsonPayload {

  public function handle($request, Closure $next) {
    if ($request->method() === 'POST') {
      if (empty($request->getContent())) {
        return Responder::respond(StatusCodes::MISSING_REQUEST, 'Missing request body');
      }


      json_decode($request->getContent(), true);

      if (json_last_error() !== JSON_ERROR_NONE) {
        return Responder::respond(StatusCodes::BAD_REQUEST, 'Invalid request body JSON');
      }
    }


    return $next($request);
  }

}