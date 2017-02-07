<?php

namespace App\MS\Services\User;

use App\MS\Helpers\Media;
use App\MS\Models\Like;
use App\MS\Models\Post;
use App\MS\Models\Relationship;
use App\MS\StatusCodes;
use App\MS\Responder;
use App\MS\Validation as V;
use App\MS\Models\Token;
use App\MS\Models\User\User;
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
      'avatar' => url('/api/media/display/' . $user->avatar),
      'posts' => $user->credential->posts
    ];

    foreach ($profile['posts'] as $post) {
      $post->likes = Like::where('postid', $post->id)->count();
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $profile);
  }



  public static function update($payload) {
    V::validate($payload, array_merge(V::name));

    $token = Token::where('token', $payload['token'])->first();

    $user = $token->credential->user;

    $user->name = $payload['name'];

    if (!empty($payload['avatar'])) {
      $user->avatar = Media::saveImage($payload['avatar'], [$user->id], 'avatar');
    }

    $user->save();

    $user->avatar = url('/api/media/display/' . $user->avatar);

    return Responder::respond(StatusCodes::SUCCESS, 'Account updated', $user);
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

}