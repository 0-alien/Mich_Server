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


    $options = ['cluster' => 'eu', 'encrypted' => true];
    $pusher = new \Pusher(env('PUSHER_KEY'), env('PUSHER_SECRET'), env('PUSHER_APP_ID'), $options);
    $pusher->trigger(''.$battle->guest, 'invitation', ['battle' => $battle->id]);


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

    $options = ['cluster' => 'eu', 'encrypted' => true];
    $pusher = new \Pusher(env('PUSHER_KEY'), env('PUSHER_SECRET'), env('PUSHER_APP_ID'), $options);
    $pusher->trigger(''.$battle->host, 'accept', ['battle' => $battle->id]);



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