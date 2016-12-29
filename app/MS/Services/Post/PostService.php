<?php

namespace App\MS\Services\Post;

use App\MS\Models\Post\Post;
use App\MS\Models\Token;
use App\MS\Responder;
use App\MS\StatusCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostService {

  public static function create(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'title' => 'required'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }


    $token = Token::where('token', $payload->token)->first();
    $post = new Post();
    $post->user_id = $token->credential->id;
    $post->title = $payload->title;
    $post->save();


    return Responder::respond(StatusCodes::SUCCESS, 'Post created successfully', $post);
  }



  public static function get(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'postID' => 'required|numeric|exists:posts,id'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }


    $post = Post::where('id', $payload->postID)->first();

    return Responder::respond(StatusCodes::SUCCESS, '', $post);
  }



  public static function delete(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'postID' => 'required|numeric|exists:posts,id'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }


    $token = Token::where('token', $payload->token)->first();
    $post = Post::where('id', $payload->postID)->first();

    if ($post->user_id !== $token->id) {
      return Responder::respond(StatusCodes::NO_PERMISSION, 'You don`t have permission to delete this post');
    }


    $post->delete();

    return Responder::respond(StatusCodes::SUCCESS, 'Post deleted successfully');
  }

}