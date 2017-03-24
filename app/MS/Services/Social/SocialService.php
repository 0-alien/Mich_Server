<?php

namespace App\MS\Services\Social;

use App\MS\Models\Post;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

class SocialService {

  public static function share($payload) {
    V::validate($payload, V::postID);

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }


    $post = Post::where('id', $payload['postID'])->first();
    $image = url('/api/media/display/' . $post->image) . '?v=' . str_random(20);

    return Responder::respond(StatusCodes::SUCCESS, '', $image);
  }

}