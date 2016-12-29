<?php

namespace App\Http\Controllers\Post;

use App\MS\Services\Post\PostService;
use Illuminate\Http\Request;

class PostController {

  public function create(Request $request) {
    return PostService::create($request);
  }



  public function get(Request $request) {
    return PostService::get($request);
  }



  public function delete(Request $request) {
    return PostService::delete($request);
  }



  public function mich(Request $request) {
    return PostService::mich($request);
  }



  public function unmich(Request $request) {
    return PostService::unmich($request);
  }

}