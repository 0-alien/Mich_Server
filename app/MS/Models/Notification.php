<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

  protected $fillable = ['type', 'itemid', 'message', 'status', 'userid'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'userid');
  }

}