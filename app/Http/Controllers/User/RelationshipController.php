<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use App\MS\Responder;
use App\MS\Services\User\RelationshipService;
use App\MS\StatusCodes;

class RelationshipController extends BaseController {

  public function follow() {
    return RelationshipService::follow($this->payload);
  }



  public function unfollow() {
    return RelationshipService::unfollow($this->payload);
  }



  public function isFollowing() {
    $result = RelationshipService::isFollowing($this->payload);

    $message = 'You are'. ($result ? ' ' : ' not ') .'following this user';
    return Responder::respond(StatusCodes::SUCCESS, $message, ['result' => $result]);
  }



  public function isFollower() {
    $result = RelationshipService::isFollower($this->payload);

    $message = 'This user is'. ($result ? ' ' : ' not ') .'following you';
    return Responder::respond(StatusCodes::SUCCESS, $message, ['result' => $result]);
  }



  public function getFollowers() {
    return RelationshipService::getFollowers($this->payload);
  }



  public function getFollowing() {
    return RelationshipService::getFollowing($this->payload);
  }

}