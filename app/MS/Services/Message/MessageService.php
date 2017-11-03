<?php

namespace App\MS\Services\Message;

use App\MS\Models\Message\Message;
use App\MS\Models\Token;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

class MessageService {

  public static function getMine($payload) {
    $token = Token::where('token', $payload['token'])->first();

    $messages = Message::where('host', $token->id)->orWhere('guest', $token->id)->get();

    $conversations = [];

    foreach ($messages as $message) {
      $userCredential = ($token->id === $message->host ? $message->guestCredential : $message->hostCredential);

      $conversation = [
        'id' => $message->id,
        'user' => [
          'id' => $userCredential->id,
          'username' => $userCredential->username,
          'avatar' => url('/api/media/display/' . $userCredential->user->avatar) . '?v=' . str_random(20),
        ]
      ];

      array_push($conversations, $conversation);
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $conversations);
  }



  public static function get($payload) {
    V::validate($payload, V::reqUserID);

    $token = Token::where('token', $payload['token'])->first();

    if ($payload['userID'] == $token->id) {
      return Responder::respond(StatusCodes::NO_PERMISSION, 'You can not message yourself');
    }

    $message = Message::whereIn('host', [$token->id, $payload['userID']])->whereIn('guest', [$token->id, $payload['userID']])->first();

    if (is_null($message)) {
      $message = new Message();
      $message->host = $token->id;
      $message->guest = $payload['userID'];
      $message->save();
    }

    $userCredential = ($token->id === $message->host ? $message->guestCredential : $message->hostCredential);

    $conversation = [
      'id' => $message->id,
      'user' => [
        'id' => $userCredential->id,
        'username' => $userCredential->username,
        'avatar' => url('/api/media/display/' . $userCredential->user->avatar) . '?v=' . str_random(20),
      ]
    ];

    return Responder::respond(StatusCodes::SUCCESS, 'Conversation started', $conversation);
  }

}