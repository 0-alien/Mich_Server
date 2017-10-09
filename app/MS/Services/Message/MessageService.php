<?php

namespace App\MS\Services;
use App\MS\Models\Message\Message;
use App\MS\Models\Token;
use App\MS\Models\User\Credential;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

class MessageService {

  public static function getMine($payload) {
    V::validate($payload, V::messageID);

    if (!Message::where('id', $payload['messageID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Conversation not found');
    }

    $token = Token::where('token', $payload['token'])->first();

    $messages = Message::where('host', $token->id)->orWhere('guest', $token->id)->get();

    return Responder::respond(StatusCodes::SUCCESS, '', $messages);
  }



  public static function create($payload) {
    V::validate($payload, V::reqUserID);

    $token = Token::where('token', $payload['token'])->first();

    if (!Credential::where('id', $payload['userID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }

    if ($payload['userID'] == $token->id) {
      return Responder::respond(StatusCodes::NO_PERMISSION, 'You can not message yourself');
    }

    if (Message::where('host', $token->id)->where('guest', $payload['userID'])-exists()) {
      return Responder::respond(StatusCodes::ALREADY_EXISTS, 'You have already started the conversation');
    }

    return Responder::respond(StatusCodes::SUCCESS, 'Conversation started');
  }

}