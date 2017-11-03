<?php

namespace App\MS\Models\Message;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

  protected $fillable = ['host', 'guest', 'status'];



  public function hostCredential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'host');
  }


  public function guestCredential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'guest');
  }

}