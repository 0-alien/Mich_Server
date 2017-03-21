<?php

namespace App\MS\Services\Notification;

use App\MS\Models\Notification;
use App\MS\Models\Token;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

class NotificationService {

  public static function get($payload) {
    $token = Token::where('token', $payload['token'])->first();

    $notifications = Notification::where('userid', $token->id)->where('status', 0)->get();

    return Responder::respond(StatusCodes::SUCCESS, '', $notifications);
  }



  public static function getAll($payload) {
    $token = Token::where('token', $payload['token'])->first();

    $notifications = Notification::where('userid', $token->id)->get();

    return Responder::respond(StatusCodes::SUCCESS, '', $notifications);
  }



  public static function seen($payload) {
    V::validate($payload, V::notificationID);

    $token = Token::where('token', $payload['token'])->first();

    $notification = Notification::where('id', $payload['notificationID'])->first();

    if ($notification->userid != $token->id) {
      return Responder::respond(StatusCodes::NO_PERMISSION, 'You do not have permission on this notification');
    }

    $notification->status = 1;
    $notification->save();

    return Responder::respond(StatusCodes::SUCCESS, 'Notification seen');
  }



  public static function seenAll($payload) {
    $token = Token::where('token', $payload['token'])->first();

    Notification::where('userid', $token->id)->update(['status' => 1]);

    return Responder::respond(StatusCodes::SUCCESS, 'All notifications seen');
  }

}