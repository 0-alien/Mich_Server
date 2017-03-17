<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\BaseController;

use App\MS\Services\Post\PostService;

class PostController extends BaseController {

  public function create() {
    return PostService::create($this->payload);
  }



  public function get() {
    return PostService::get($this->payload);
  }



  public function delete() {
    return PostService::delete($this->payload);
  }



  public function comments() {
    return PostService::comments($this->payload);
  }



  public function feed() {
    return PostService::feed($this->payload);
  }



  public function explore() {
    return PostService::explore($this->payload);
  }



  public function like() {
    return PostService::like($this->payload);
  }



  public function unlike() {
    return PostService::unlike($this->payload);
  }



  public function comment() {
    return PostService::comment($this->payload);
  }



  public function likeComment() {
    return PostService::likeComment($this->payload);
  }



  public function unlikeComment() {
    return PostService::unlikeComment($this->payload);
  }

}