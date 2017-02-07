<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;

use App\MS\Services\User\UserService;

class UserController extends BaseController {

  public function get() {
    return UserService::get($this->payload);
  }



  public function update() {
    return UserService::update($this->payload);
  }



  public function delete() {
    return UserService::delete($this->payload);
  }



  public function changePassword() {
    return UserService::changePassword($this->payload);
  }

}