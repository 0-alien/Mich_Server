<?php

namespace App\MS\Services\User;

use App\MS\Models\Notification;
use App\MS\Models\Relationship;
use App\MS\Models\Report;
use App\MS\Models\Token;
use App\MS\Models\User\Credential;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

class RelationshipService {

  private static function VuserID($payload) {
    $userID = V::userID;
    $userID['userID'] .= '|required';

    V::validate($payload, $userID);
  }


  public static function isFollowing($payload) {
    self::VuserID($payload);

    if (!Credential::where('id', $payload['userID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }


    $token = Token::where('token', $payload['token'])->first();

    if ($token->id == $payload['userID']) {
      return true;
    }

    return Relationship::where('follower', $token->id)->where('following', $payload['userID'])->exists();
  }



  public static function isFollower($payload) {
    self::VuserID($payload);

    if (!Credential::where('id', $payload['userID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }


    $token = Token::where('token', $payload['token'])->first();

    if ($token->id == $payload['userID']) {
      return true;
    }

    return Relationship::where('follower', $payload['userID'])->where('following', $token->id)->exists();
  }



  public static function follow($payload) {
    self::VuserID($payload);

    if (!Credential::where('id', $payload['userID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }


    $token = Token::where('token', $payload['token'])->first();

    if (!self::isFollowing($payload) && $token->id != $payload['userID']) {
      $relationship = new Relationship();
      $relationship->follower = $token->id;
      $relationship->following = $payload['userID'];
      $relationship->save();


      $notification = new Notification();
      $notification->type = 4;
      $notification->followerid = $relationship->follower;
      $notification->message = $token->credential->username . ' is following you';
      $notification->avatar = url('/api/media/display/' . $token->credential->user->avatar);
      $notification->userid = $relationship->following;
      $notification->save();
      $notification->send();
    }

    return Responder::respond(StatusCodes::SUCCESS, 'You are following this user');
  }



  public static function unfollow($payload) {
    self::VuserID($payload);

    if (!Credential::where('id', $payload['userID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }


    $token = Token::where('token', $payload['token'])->first();

    if ($token->id == $payload['userID']) {
      return Responder::respond(StatusCodes::INVALID_PARAMETER, 'You can not unfollow yourself');
    }

    Relationship::where('follower', $token->id)->where('following', $payload['userID'])->delete();

    return Responder::respond(StatusCodes::SUCCESS, 'You are no longer following this user');
  }



  public static function getFollowers($payload) {
    V::validate($payload, V::userID);

    if (!empty($payload['userID']) && !Credential::where('id', $payload['userID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }


    $token = Token::where('token', $payload['token'])->first();
    $credentialID = (!empty($payload['userID']) ? $payload['userID'] : $token->id);
    $credential = Credential::where('id', $credentialID)->first();
    $blockers = self::getBlockers($token->id);

    $relationships = $credential->followers;
    $followers = [];

    foreach ($relationships as $relationship) {
      $followerCredential = $relationship->followerUser;
      $followerUser = $followerCredential->user;

      if (in_array($followerCredential->id, $blockers)) continue;

      $followers[] = [
        'id' => $followerCredential->id,
        'name' => $followerUser->name,
        'username' => $followerCredential->username,
        'email' => $followerCredential->email,
        'avatar' => url('/api/media/display/' . $followerUser->avatar)
      ];
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $followers);
  }



  public static function getFollowing($payload) {
    V::validate($payload, V::userID);

    if (!empty($payload['userID']) && !Credential::where('id', $payload['userID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }


    $token = Token::where('token', $payload['token'])->first();
    $credentialID = (!empty($payload['userID']) ? $payload['userID'] : $token->id);
    $credential = Credential::where('id', $credentialID)->first();
    $blockers = self::getBlockers($token->id);

    $relationships = $credential->following;
    $following = [];

    foreach ($relationships as $relationship) {
      $followingCredential = $relationship->followingUser;
      $followingUser = $followingCredential->user;

      if (in_array($followingCredential->id, $blockers)) continue;

      $following[] = [
        'id' => $followingCredential->id,
        'name' => $followingUser->name,
        'username' => $followingCredential->username,
        'email' => $followingCredential->email,
        'avatar' => url('/api/media/display/' . $followingUser->avatar)
      ];
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $following);
  }













  private static function getBlockers($id) {
    $reports = Report::where('type', 2)->where('item', $id)->get();

    $blockers = [];

    foreach ($reports as $report) {
      array_push($blockers, $report->userid);
    }

    return $blockers;
  }

}