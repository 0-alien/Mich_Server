<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Comlike extends Model {

  protected $fillable = ['userid', 'commentid'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'userid');
  }


  public function comment() {
    return $this->hasOne('\App\MS\Models\Comment', 'id', 'commentid');
  }

}