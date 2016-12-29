<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;

use App\MS\Services\Post\FeedService;

class FeedController {

  public function get(Request $request) {
    return FeedService::get($request);
  }

}