<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;

use App\MS\Services\User\UserService;

class UserController extends BaseController {

  public function get() {
    return UserService::get($this->payload);
  }

}