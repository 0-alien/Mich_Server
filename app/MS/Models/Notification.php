<?php

namespace App\MS\Models;

use App\MS\Helpers\FCM;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

  protected $fillable = ['type', 'itemid', 'message', 'avatar', 'status', 'userid'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'userid');
  }



  public function send() {
    $badge = Notification::where('userid', $this->userid)->where('status', 0)->count();
    $data = ['type' => $this->type, 'id' => $this->itemid, 'notificationid' => $this->id];
    if ($this->type === 2 || $this->type === 3) {
      $data['commentid'] = $data['id'];
      unset($data['id']);
      $data['postid'] = Comment::where('id', $this->itemid)->first()->post->id;
    }
    FCM::send(Token::where('id', $this->userid)->first()->fcmrt, $this->message, $this->message, $data, $badge);
  }

}