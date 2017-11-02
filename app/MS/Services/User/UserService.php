<?php

namespace App\MS\Services\User;

use App\MS\Helpers\Media;
use App\MS\Models\Block;
use App\MS\Models\Comment;
use App\MS\Models\Like;
use App\MS\Models\Post;
use App\MS\Models\Relationship;
use App\MS\Models\Report;
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

    $blocked = Block::where('userid', $token->id)->where('blockid', $user->id)->exists();

    $profile = [
      'id' => $userID,
      'name' => $user->name,
      'username' => $user->credential->username,
      'email' => $user->credential->email,
      'avatar' => url('/api/media/display/' . $user->avatar) . '?v=' . str_random(20),
      'nfollowers' => Relationship::where('following', $userID)->count(),
      'nfollowing' => Relationship::where('follower', $userID)->count(),
      'blocked' => $blocked,
      'win' => $user->win,
      'draw' => $user->draw,
      'loss' => $user->loss
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

    if (!empty($payload['username']) && $payload['username'] != $user->credential->username) {
      V::validate($payload, V::username);

      if (Credential::where('username', $payload['username'])->exists()) {
        return Responder::respond(StatusCodes::ALREADY_EXISTS, 'This username already exists');
      }

      $credential = $user->credential;
      $credential->username = $payload['username'];
      $credential->save();
    }

    $user->save();

    $profile = [
      'id' => $user->id,
      'name' => $user->name,
      'username' => $user->credential->username,
      'email' => $user->credential->email,
      'avatar' => url('/api/media/display/' . $user->avatar) . '?v=' . str_random(20),
      'nfollowers' => Relationship::where('following', $user->id)->count(),
      'nfollowing' => Relationship::where('follower', $user->id)->count(),
      'win' => $user->win,
      'draw' => $user->draw,
      'loss' => $user->loss
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
    V::validate($payload, array_merge(V::password, ['oldPassword' => 'required|min:6|max:50']));

    $token = Token::where('token', $payload['token'])->first();

    $credential = $token->credential;

    if (!Hash::check($payload['oldPassword'], $credential->password)) {
      return Responder::respond(StatusCodes::INVALID_CREDENTIALS, 'Invalid old password');
    }

    $credential->password = Hash::make($payload['password']);
    $credential->save();

    return Responder::respond(StatusCodes::SUCCESS, 'Password changed');
  }



  public static function posts($payload) {
    V::validate($payload, V::userID);

    $token = Token::where('token', $payload['token'])->first();

    $userID = (isset($payload['userID']) ? $payload['userID'] : $token->id);

    $payload['userID'] = $userID;

    if (Credential::find($userID)->private && (!RelationshipService::isFollower($payload) || !RelationshipService::isFollowing($payload))) {
      return Responder::respond(StatusCodes::NO_PERMISSION, 'This account is private');
    }

    if (!User::where('id', $userID)->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }


    $posts = Post::where('userid', $userID)->orderBy('id', 'desc')->get();

    foreach ($posts as $post) {
      $post->image = url('/api/media/display/' . $post->image) . '?v=' . str_random(20);
      $post->likes = Like::where('postid', $post->id)->count();
      $post->mylike = (Like::where('postid', $post->id)->where('userid', $token->id)->exists() ? 1 : 0);
      $post->ncomments = Comment::where('postid', $post->id)->count();
      $post->username = $post->credential->username;
      $post->avatar = url('/api/media/display/' . $post->credential->user->avatar) . '?v=' . str_random(20);
      unset($post->credential);
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $posts);
  }



  public static function report($payload) {
    V::validate($payload, V::userID);

    if (!User::where('id', $payload['userID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }

    $token = Token::where('token', $payload['token'])->first();


    $report = new Report();
    $report->userid = $token->id;
    $report->type = 2;
    $report->item = $payload['userID'];
    $report->save();
    $report->notify();

    return Responder::respond(StatusCodes::SUCCESS, 'User reported');
  }



  public static function block($payload) {
    V::validate($payload, V::userID);

    if (!User::where('id', $payload['userID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }

    $token = Token::where('token', $payload['token'])->first();


    $block = new Block();
    $block->userid = $token->id;
    $block->blockid = $payload['userID'];
    $block->save();

    return Responder::respond(StatusCodes::SUCCESS, 'User blocked');
  }



  public static function unblock($payload) {
    V::validate($payload, V::userID);

    if (!User::where('id', $payload['userID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }

    $token = Token::where('token', $payload['token'])->first();

    Block::where('userid', $token->id)->where('blockid', $payload['userID'])->delete();

    return Responder::respond(StatusCodes::SUCCESS, 'User unblocked');
  }



  public static function toggleStatus($payload) {
    $token = Token::where('token', $payload['token'])->first();
    $credential = $token->credential;
    $credential->private = 1 - $credential->private;
    $credential->save();
    $statuses = ['public', 'private'];
    return Responder::respond(StatusCodes::SUCCESS, 'You account is now ' . $statuses[$credential->private]);
  }

}