<?php

namespace App\MS;

use Illuminate\Support\Facades\Validator;

class Validation {

  const username      = ['username' => 'required|alpha_num|min:2|max:50'];
  const email         = ['email' => 'required|email|max:100'];
  const mixed         = ['username' => 'required|max:100'];
  const password      = ['password' => 'required|min:6|max:50'];
  const name          = ['name' => 'required|alpha_spaces|min:2|max:100'];
  const loginType     = ['type' => 'required|numeric|in:0,1,2'];
  const userID        = ['userID' => 'numeric'];
  const token         = ['token' => 'required|size:64'];
  const code          = ['code' => 'required|digits:6'];
  const title         = ['title' => 'required|min:2|max:200'];
  const image         = ['image' => 'required'];
  const postID        = ['postID' => 'required'];
  const term          = ['term' => 'required|alpha_num|min:1|max:50'];
  const comment       = ['comment' => 'required|min:1|max:500'];

  const MESSAGES = [
    'required' => 'The [:attribute] parameter is required',
    'alpha' => 'The [:attribute] parameter must contain only letters',
    'alpha_num' => 'The [:attribute] parameter must contain only letters and numbers',
    'alpha_spaces' => 'The [:attribute] parameter must contain only letters and spaces',
    'numeric' => 'The [:attribute] parameter must contain only numbers',
    'min' => 'The [:attribute] parameter\'s length must be at least :min',
    'max' => 'The [:attribute] parameter\'s length must not be more than :max',
    'size' => 'The [:attribute] parameter\'s length must be exactly :size',
    'in' => 'The [:attribute] parameter\'s value is invalid',
    'email' => 'The [:attribute] parameter must be a valid email',
    'exists' => 'The [:attribute] value does not exist',
    'not_exists' => 'This [:attribute] already exists',
    'digits' => 'The [:attribute] must contain only digits and have length of :digits'
  ];




  public static function validate($data, $rules) {
    $validator = Validator::make($data, $rules, self::MESSAGES);

    if (!$validator->passes()) {
      header('Content-Type: application/json');
      die(Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first())->getContent());
    }
  }

}