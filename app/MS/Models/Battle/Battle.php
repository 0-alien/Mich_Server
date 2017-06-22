<?php

namespace App\MS\Models\Battle;

use Illuminate\Database\Eloquent\Model;

class Battle extends Model {

  protected $fillable = ['host', 'guest', 'hostvotes', 'guestvotes', 'status'];



  public function hostCredential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'host');
  }


  public function guestCredential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'guest');
  }

}