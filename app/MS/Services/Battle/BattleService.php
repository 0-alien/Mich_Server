<?php

namespace App\MS\Services\Battle;

use App\MS\Models\Battle\Battle;
use App\MS\Models\Token;
use App\MS\Models\User\Credential;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

class BattleService {

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

    return Responder::respond(StatusCodes::SUCCESS, 'Battle accepted');
  }

}