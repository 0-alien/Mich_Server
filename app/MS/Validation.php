<?php

namespace App\MS;

use Illuminate\Support\Facades\Validator;

class Validation {

  /* Parameters */
  const username_p      = ['username' => 'required|alpha_num|max:50'];
  const email_p         = ['email' => 'required|email|max:100'];
  const mixed_p         = ['username' => 'required|max:100'];
  const password_p      = ['password' => 'required|min:6|max:50'];
  const firstname_p     = ['firstname' => 'required|alpha|min:2|max:50'];
  const lastname_p      = ['lastname' => 'required|alpha|min:2|max:50'];
  const type_p          = ['type' => 'required|numeric|in:0,1,2'];
  const userID_p        = ['userID' => 'numeric'];



  /* Messages */
  const required_m      = ['required' => 'The [:attribute] parameter is required'];
  const alpha_m         = ['alpha' => 'The [:attribute] parameter must contain only letters'];
  const alpha_num_m     = ['alpha_num' => 'The [:attribute] parameter must contain only letters and numbers'];
  const numeric_m       = ['numeric' => 'The [:attribute] parameter must contain only numbers'];
  const min_m           = ['min' => 'The [:attribute] parameter\'s length must be at least :min'];
  const max_m           = ['max' => 'The [:attribute] parameter\'s length must not be more than :max'];
  const size_m          = ['size' => 'The [:attribute] parameter\'s length must be exactly :size'];
  const in_m            = ['in' => 'The [:attribute] parameter\'s value is invalid'];
  const email_m         = ['email' => 'The [:attribute] parameter must be a valid email'];
  const exists_m        = ['exists' => 'The [:attribute] value does not exist'];
  const not_exists_m    = ['not_exists' => 'This [:attribute] already exists'];




  public static function validate($data, $validation) {
    $validator = Validator::make($data, $validation['parameters'], $validation['messages']);

    if (!$validator->passes()) {
      header('Content-Type: application/json');
      die(Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first())->getContent());
    }
  }




  public static function getRegister() {
    $username = self::username_p;
    $username['username'] .= '|not_exists:credentials,username';

    $email = self::email_p;
    $email['email'] .= '|not_exists:credentials,email';

    return [
      'parameters' => array_merge($username, $email, self::password_p, self::firstname_p, self::lastname_p),
      'messages'   => array_merge(self::required_m, self::alpha_num_m, self::max_m, self::not_exists_m, self::email_m, self::min_m, self::alpha_m)
    ];
  }



  public static function getPreLogin() {
    return [
      'parameters' => array_merge(self::type_p, self::mixed_p, self::password_p),
      'messages'   => array_merge(self::required_m, self::numeric_m, self::max_m, self::min_m)
    ];
  }



  public static function getUser() {
    $userID = self::userID_p;
    $userID['userID'] .= '|exists:credentials,id';

    return [
      'parameters' => array_merge(self::userID_p),
      'messages' => array_merge(self::numeric_m, self::exists_m)
    ];
  }

}