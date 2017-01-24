<?php

namespace App\MS;

use Illuminate\Support\Facades\Validator;

class Validation {

  const username      = ['username' => 'required|alpha_num|max:50'];
  const email         = ['email' => 'required|email|max:100'];
  const mixed         = ['username' => 'required|max:100'];
  const password      = ['password' => 'required|min:6|max:50'];
  const firstname     = ['firstname' => 'required|alpha|min:2|max:50'];
  const lastname      = ['lastname' => 'required|alpha|min:2|max:50'];
  const loginType     = ['type' => 'required|numeric|in:0,1,2'];
  const userID        = ['userID' => 'numeric'];

  const MESSAGES = [
    'required' => 'The [:attribute] parameter is required',
    'alpha' => 'The [:attribute] parameter must contain only letters',
    'alpha_num' => 'The [:attribute] parameter must contain only letters and numbers',
    'numeric' => 'The [:attribute] parameter must contain only numbers',
    'min' => 'The [:attribute] parameter\'s length must be at least :min',
    'max' => 'The [:attribute] parameter\'s length must not be more than :max',
    'size' => 'The [:attribute] parameter\'s length must be exactly :size',
    'in' => 'The [:attribute] parameter\'s value is invalid',
    'email' => 'The [:attribute] parameter must be a valid email',
    'exists' => 'The [:attribute] value does not exist',
    'not_exists' => 'This [:attribute] already exists'
  ];




  public static function validate($data, $rules) {
    $validator = Validator::make($data, $rules, self::MESSAGES);

    if (!$validator->passes()) {
      header('Content-Type: application/json');
      die(Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first())->getContent());
    }
  }

}