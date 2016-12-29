<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\MS\Services\User\UserService;

class UserController {

  public function get(Request $request) {
    return UserService::get($request);
  }



  public function update(Request $request) {
    return UserService::update($request);
  }



  public function delete(Request $request) {
    return UserService::delete($request);
  }

}