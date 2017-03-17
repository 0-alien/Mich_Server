<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

  protected $fillable = ['userid', 'type', 'message', 'status'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'userid');
  }

}