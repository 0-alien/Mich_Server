<?php

namespace App\Http\Middleware;

use App\MS\StatusCodes;
use Closure;

use Illuminate\Support\Facades\Response;

class JsonPayload
{

  public function handle($request, Closure $next)
  {
    json_decode($request->payload);

    if (json_last_error() !== JSON_ERROR_NONE)
    {
      $response = [
        'code' => StatusCodes::INVALID_PAYLOAD,
        'message' => StatusCodes::$MESSAGE[StatusCodes::INVALID_PAYLOAD]
      ];

      $response = json_encode($response);

      return Response::make($response, 200)->header('Content-Type', 'application/json');
    }


    return $next($request);
  }

}