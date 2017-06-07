<?php

namespace App\MS\Services\Battle;

use App\MS\Models\Battle\Battle;
use App\MS\Models\Notification;
use App\MS\Models\Token;
use App\MS\Models\User\Credential;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

class BattleService {

  public static function get($payload) {
    V::validate($payload, V::battleID);

    if (!Battle::where('id', $payload['battleID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Battle not found');
    }

    $token = Token::where('token', $payload['token'])->first();

    $battle = Battle::where('id', $payload['battleID'])->first();

    $battle->mybattle = false;
    $battle->iamhost = false;
    $battle->iamguest = false;

    if ($token->id === $battle->host) {
      $battle->mybattle = true;
      $battle->iamhost = true;
    } else if ($token->id === $battle->guest) {
      $battle->mybattle = true;
      $battle->iamguest = true;
    }

    $battle->host = ['id' => $battle->host, 'username' => $battle->hostCredential->username, 'avatar' => url('/api/media/display/' . $battle->hostCredential->user->avatar) . '?v=' . str_random(20)];
    $battle->guest = ['id' => $battle->guest, 'username' => $battle->guestCredential->username, 'avatar' => url('/api/media/display/' . $battle->guestCredential->user->avatar) . '?v=' . str_random(20)];
    unset($battle->hostCredential);
    unset($battle->guestCredential);

    return Responder::respond(StatusCodes::SUCCESS, '', $battle);
  }



  public static function getAll($payload) {
    $token = Token::where('token', $payload['token'])->first();

    $battles = Battle::orderBy('id', 'desc')->get();

    foreach ($battles as $battle) {
      $battle->mybattle = false;
      $battle->iamhost = false;
      $battle->iamguest = false;

      if ($token->id === $battle->host) {
        $battle->mybattle = true;
        $battle->iamhost = true;
      } else if ($token->id === $battle->guest) {
        $battle->mybattle = true;
        $battle->iamguest = true;
      }

      $battle->host = ['id' => $battle->host, 'username' => $battle->hostCredential->username, 'avatar' => url('/api/media/display/' . $battle->hostCredential->user->avatar) . '?v=' . str_random(20)];
      $battle->guest = ['id' => $battle->guest, 'username' => $battle->guestCredential->username, 'avatar' => url('/api/media/display/' . $battle->guestCredential->user->avatar) . '?v=' . str_random(20)];
      unset($battle->hostCredential);
      unset($battle->guestCredential);
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $battles);
  }




  public static function invite($payload) {
    V::validate($payload, V::reqUserID);

    $token = Token::where('token', $payload['token'])->first();

    if (!Credential::where('id', $payload['userID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }

    if ($payload['userID'] == $token->id) {
      return Responder::respond(StatusCodes::NO_PERMISSION, 'You can not invite yourself');
    }


    $battle = new Battle();
    $battle->host = $token->id;
    $battle->guest = $payload['userID'];
    $battle->save();

    $notification = new Notification();
    $notification->type = 5;
    $notification->battleid = $battle->id;
    $notification->message = $battle->hostCredential->username . ' invited you to the battle';
    $notification->avatar = url('/api/media/display/' . $battle->hostCredential->user->avatar);
    $notification->userid = $battle->guest;
    $notification->save();
    $notification->send();


    return Responder::respond(StatusCodes::SUCCESS, 'Invitation sent');
  }



  public static function accept($payload) {
    V::validate($payload, V::battleID);

    $token = Token::where('token', $payload['token'])->first();

    if (!Battle::where('id', $payload['battleID'])->where('status', 1)->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Battle not found');
    }

    $battle = Battle::where('id', $payload['battleID'])->first();

    if ($battle->guest != $token->id) {
      return Responder::respond(StatusCodes::NO_PERMISSION, 'You are not invited to this battle');
    }


    $battle->status = 2;
    $battle->save();

    $notification = new Notification();
    $notification->type = 6;
    $notification->battleid = $battle->id;
    $notification->message = $battle->guestCredential->username . ' accepted your invitation';
    $notification->avatar = url('/api/media/display/' . $battle->guestCredential->user->avatar);
    $notification->userid = $battle->host;
    $notification->save();
    $notification->send();


    return Responder::respond(StatusCodes::SUCCESS, 'Battle accepted');
  }

}