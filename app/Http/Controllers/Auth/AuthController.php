<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;

use App\MS\Validation as V;
use App\MS\Services\Auth\AuthService;

class AuthController extends BaseController {

  public function register() {
    return AuthService::register($this->payload);
  }



  public function login() {
    V::validate($this->payload, array_merge(V::loginType, V::mixed, V::password));

    if ($this->payload['type'] === 0) {
      return AuthService::login($this->payload);
    }
  }



  public function logout() {
    return AuthService::logout($this->payload);
  }

}