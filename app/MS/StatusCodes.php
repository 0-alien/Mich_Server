<?php

namespace App\MS;

class StatusCodes {

  const SUCCESS = 10;

  const INVALID_PAYLOAD = 20;
  const BAD_REQUEST = 21;
  const NOT_FOUND = 22;
  const TOKEN_MISSING = 23;
  const INVALID_TOKEN = 24;

  const ALREADY_EXISTS = 30;
  const INVALID_PARAMETER = 31;
  const NO_PERMISSION = 32;




  public static $MESSAGE = [
    10 => 'success',
    20 => 'invalid payload',

    21 => 'bad request',
    22 => 'not found',
    23 => 'token missing',
    24 => 'invalid token',

    30 => 'already exists',
    31 => 'invalid parameter',
    32 => 'no permission',
  ];

}