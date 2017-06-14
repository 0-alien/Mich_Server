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



  public function posts() {
    return UserService::posts($this->payload);
  }



  public function report() {
    return UserService::report($this->payload);
  }



  public function block() {
    return UserService::block($this->payload);
  }


  public function unblock() {
    return UserService::unblock($this->payload);
  }

}