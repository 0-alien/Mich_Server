<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model {

  protected $fillable = ['userid', 'blockid'];



  public function user() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'userid');
  }


  public function blockedUser() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'blockid');
  }

}