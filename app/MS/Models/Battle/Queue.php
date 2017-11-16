<?php

namespace App\MS\Models\Battle;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model {

  protected $fillable = ['host', 'guest', 'battle'];



  public function hostCredential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'host');
  }


  public function guestCredential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'guest');
  }


  public function battleObject() {
    return $this->hasOne('\App\MS\Models\Battle\Battle', 'id', 'battle');
  }

}