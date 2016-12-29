<?php

namespace App\MS\Services\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\MS\Models\Token;
use App\MS\Models\User\User;
use App\MS\Responder;
use App\MS\StatusCodes;

class RelationService {

  public static function follow(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'userID' => 'numeric|exists:credentials,id'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }


    $token = Token::where('token', $payload->token)->first();
    $currentUser = $token->credential->user;
    $user = User::where('id', $payload->userID)->first();

    if ($user->id !== $currentUser->id) {
      $currentUserFollowing = json_decode($currentUser->following);
      if (!in_array($user->id, $currentUserFollowing)) {
        array_push($currentUserFollowing, $user->id);
        $currentUser->following = json_encode($currentUserFollowing);
        $currentUser->save();
      }


      $userFollowers = json_decode($user->followers);
      if (!in_array($currentUser->id, $userFollowers)) {
        array_push($userFollowers, $currentUser->id);
        $user->followers = json_encode($userFollowers);
        $user->save();
      }
    }

    return Responder::respond(StatusCodes::SUCCESS, '');
  }



  public static function unfollow(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'userID' => 'numeric|exists:credentials,id'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }


    $token = Token::where('token', $payload->token)->first();
    $currentUser = $token->credential->user;
    $user = User::where('id', $payload->userID)->first();

    if ($user->id !== $currentUser->id) {
      $currentUserFollowing = json_decode($currentUser->following);
      if (($key = array_search($user->id, $currentUserFollowing)) !== false) {
        unset($currentUserFollowing[$key]);
        $currentUser->following = json_encode($currentUserFollowing);
        $currentUser->save();
      }


      $userFollowers = json_decode($user->followers);
      if (($key = array_search($currentUser->id, $userFollowers)) !== false) {
        unset($userFollowers[$key]);
        $user->followers = json_encode($userFollowers);
        $user->save();
      }
    }

    return Responder::respond(StatusCodes::SUCCESS, '');
  }



  public static function following(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'userID' => 'numeric|exists:credentials,id'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }


    $token = Token::where('token', $payload->token)->first();
    $currentUser = $token->credential->user;
    $user = User::where('id', $payload->userID)->first();

    $currentUserFollowing = json_decode($currentUser->following);

    $data = ['following' => false];
    $message = 'You are not following this user';

    if (in_array($user->id, $currentUserFollowing)) {
      $data['following'] = true;
      $message = 'You are following this user';
    }

    return Responder::respond(StatusCodes::SUCCESS, $message, $data);
  }



  public static function follower(Request $request) {
    $payload = json_decode($request->payload);
    $payloadArray = json_decode($request->payload, true);

    $validator = Validator::make($payloadArray, [
      'userID' => 'numeric|exists:credentials,id'
    ]);

    if (!$validator->passes()) {
      return Responder::respond(StatusCodes::BAD_REQUEST, $validator->messages()->first());
    }


    $token = Token::where('token', $payload->token)->first();
    $currentUser = $token->credential->user;
    $user = User::where('id', $payload->userID)->first();

    $currentUserFollowers = json_decode($currentUser->followers);

    $data = ['follower' => false];
    $message = 'This user is not following you';

    if (in_array($user->id, $currentUserFollowers)) {
      $data['follower'] = true;
      $message = 'This user is following you';
    }

    return Responder::respond(StatusCodes::SUCCESS, $message, $data);
  }

}