<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;

use App\MS\Services\Auth\AuthService;
use App\MS\Validation;

class AuthController extends BaseController {

  public function register() {
    return AuthService::register($this->payload);
  }



  public function login() {
    Validation::validate($this->payload, Validation::getPreLogin());

    if ($this->payload['type'] === 0) {
      return AuthService::login($this->payload);
    }
  }



  public function logout() {
    return AuthService::logout($this->payload);
  }

}