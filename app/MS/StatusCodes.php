<?php

namespace App\MS;

class StatusCodes {

  const SUCCESS = 10;

  const MISSING_REQUEST = 20;
  const BAD_REQUEST = 21;
  const NOT_FOUND = 22;
  const TOKEN_MISSING = 23;
  const INVALID_TOKEN = 24;
  const INVALID_CREDENTIALS = 25;
  const TRY_LIMIT = 26;
  const BANNED_WORD = 27;

  const ALREADY_EXISTS = 30;
  const INVALID_PARAMETER = 31;
  const NO_PERMISSION = 32;

  const IN_QUEUE = 40;




  public static $MESSAGE = [
    10 => 'success',

    20 => 'missing request body',
    21 => 'bad request',
    22 => 'not found',
    23 => 'token missing',
    24 => 'invalid token',
    25 => 'invalid credentials',
    26 => 'no more tries',
    27 => 'banned word',

    30 => 'already exists',
    31 => 'invalid parameter',
    32 => 'no permission',

    40 => 'in queue'
  ];

}