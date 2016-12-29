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
    $post->mich = '[]';
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



  public static function mich(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'postID' => 'required|numeric|exists:posts,id'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }


    $token = Token::where('token', $payload->token)->first();
    $currentUser = $token->credential->user;
    $post = Post::where('id', $payload->postID)->first();

    $currentUserMich = json_decode($currentUser->mich);
    if (!in_array($post->id, $currentUserMich)) {
      array_push($currentUserMich, $post->id);
      $currentUser->mich = json_encode($currentUserMich);
      $currentUser->save();
    }


    $postMich = json_decode($post->mich);
    if (!in_array($currentUser->id, $postMich)) {
      array_push($postMich, $currentUser->id);
      $post->mich = json_encode($postMich);
      $post->save();
    }

    return Responder::respond(StatusCodes::SUCCESS, '');
  }



  public static function unmich(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'postID' => 'required|numeric|exists:posts,id'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }


    $token = Token::where('token', $payload->token)->first();
    $currentUser = $token->credential->user;
    $post = Post::where('id', $payload->postID)->first();

    $currentUserMich = json_decode($currentUser->mich);
    if (($key = array_search($post->id, $currentUserMich)) !== false) {
      unset($currentUserMich[$key]);
      $currentUser->mich = json_encode($currentUserMich);
      $currentUser->save();
    }


    $postMich = json_decode($post->mich);
    if (($key = array_search($currentUser->id, $postMich)) !== false) {
      unset($postMich[$key]);
      $post->mich = json_encode($postMich);
      $post->save();
    }

    return Responder::respond(StatusCodes::SUCCESS, '');
  }

}