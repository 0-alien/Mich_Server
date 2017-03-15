<?php

namespace App\MS\Services\User;

use App\MS\Helpers\Media;
use App\MS\Models\Comment;
use App\MS\Models\Like;
use App\MS\Models\Relationship;
use App\MS\Models\User\Credential;
use App\MS\StatusCodes;
use App\MS\Responder;
use App\MS\Validation as V;
use App\MS\Models\Token;
use App\MS\Models\User\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserService {

  public static function get($payload) {
    V::validate($payload, V::userID);

    $token = Token::where('token', $payload['token'])->first();

    $userID = (isset($payload['userID']) ? $payload['userID'] : $token->id);

    if (!User::where('id', $userID)->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }


    $user = User::where('id', $userID)->first();

    $profile = [
      'id' => $userID,
      'name' => $user->name,
      'username' => $user->credential->username,
      'email' => $user->credential->email,
      'avatar' => url('/api/media/display/' . $user->avatar) . '?v=' . str_random(20),
      'nfollowers' => Relationship::where('following', $userID)->count(),
      'nfollowing' => Relationship::where('follower', $userID)->count(),
    ];

    return Responder::respond(StatusCodes::SUCCESS, '', $profile);
  }



  public static function update($payload) {
    file_put_contents('request.txt', print_r($payload, true));

    V::validate($payload, V::name);

    $token = Token::where('token', $payload['token'])->first();

    $user = $token->credential->user;

    if (!empty($payload['name'])) {
      $user->name = $payload['name'];
    }

    if (!empty($payload['avatar'])) {
      $user->avatar = Media::saveImage($payload['avatar'], [$user->id], 'avatar');
    }

    if (!empty($payload['email']) && $payload['email'] != $user->credential->email) {
      V::validate($payload, V::email);

      if (Credential::where('email', $payload['email'])->exists()) {
        return Responder::respond(StatusCodes::ALREADY_EXISTS, 'This email already exists');
      }

      $credential = $user->credential;
      $credential->email = $payload['email'];
      $credential->save();
    }

    $user->save();

    $profile = [
      'id' => $user->id,
      'name' => $user->name,
      'username' => $user->credential->username,
      'email' => $user->credential->email,
      'avatar' => url('/api/media/display/' . $user->avatar),
      'nfollowers' => Relationship::where('following', $user->id)->count(),
      'nfollowing' => Relationship::where('follower', $user->id)->count(),
    ];

    return Responder::respond(StatusCodes::SUCCESS, 'Account updated', $profile);
  }



  public static function delete($payload) {
    $token = Token::where('token', $payload['token'])->first();

    $user = User::where('id', $token->id)->first();

    $user->credential->delete();

    return Responder::respond(StatusCodes::SUCCESS, 'Account deleted');
  }



  public static function changePassword($payload) {
    V::validate($payload, V::password);

    $token = Token::where('token', $payload['token'])->first();

    $credential = $token->credential;
    $credential->password = Hash::make($payload['password']);
    $credential->save();

    return Responder::respond(StatusCodes::SUCCESS, 'Password changed');
  }



  public static function posts($payload) {
    V::validate($payload, V::userID);

    $token = Token::where('token', $payload['token'])->first();

    $userID = (isset($payload['userID']) ? $payload['userID'] : $token->id);

    if (!User::where('id', $userID)->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }


    $credential = Credential::where('id', $userID)->first();

    $posts = $credential->posts;

    foreach ($posts as $post) {
      $post->image = url('/api/media/display/' . $post->image);
      $post->likes = Like::where('postid', $post->id)->count();
      $post->mylike = (Like::where('postid', $post->id)->where('userid', $token->id)->exists() ? 1 : 0);
      $post->ncomments = Comment::where('postid', $post->id)->count();
      $post->username = $post->credential->username;
      $post->avatar = url('/api/media/display/' . $post->credential->user->avatar);
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $posts);
  }

}