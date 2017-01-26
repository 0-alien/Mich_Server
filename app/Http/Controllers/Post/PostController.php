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



  public function feed() {
    return PostService::feed($this->payload);
  }

}