<?php

namespace App\MS\Models;

use App\MS\Helpers\FCM;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

  protected $fillable = ['type', 'message', 'avatar', 'status', 'userid', 'postid', 'commentid', 'followerid', 'battleid'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'userid');
  }



  public function send() {
    if (!Token::where('id', $this->userid)->exists()) {
      return false;
    }

    $badge = Notification::where('userid', $this->userid)->where('status', 0)->count();
    FCM::send(Token::where('id', $this->userid)->first()->fcmrt, $this->message, $this->message, [
      'type' => $this->type,
      'postid' => $this->postid,
      'commentid' => $this->commentid,
      'followerid' => $this->followerid,
      'battleid' => $this->battleid
    ], $badge);
  }

}