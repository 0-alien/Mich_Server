<?php

namespace App\Http\Controllers\User;

use App\MS\Services\User\RelationService;
use Illuminate\Http\Request;

class RelationController {

  public function follow(Request $request) {
    return RelationService::follow($request);
  }



  public function unfollow(Request $request) {
    return RelationService::unfollow($request);
  }



  public function following(Request $request) {
    return RelationService::following($request);
  }



  public function follower(Request $request) {
    return RelationService::follower($request);
  }

}