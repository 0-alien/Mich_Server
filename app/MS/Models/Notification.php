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
    FCM::send(Token::where('id', $this->userid)->first()->fcmrt, $this->message, '', ['type' => $this->type, 'id' => $this->itemid]);
  }

}