<?php

namespace App\MS\Services\Post;

use App\MS\Models\Post\Post;
use App\MS\Models\Token;
use Illuminate\Http\Request;

class FeedService {

  public static function get(Request $request) {
    $payload = json_decode($request->payload);

    $token = Token::where('token', $payload->token)->first();

    $following = json_decode($token->credential->user->following);

    array_push($following, $token->id);

    $posts = Post::whereIn('user_id', $following)->orderBy('created_at', 'desc')->get();
  }

}