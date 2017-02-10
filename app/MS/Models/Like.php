<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model {

  protected $fillable = ['userid', 'postid'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'userid');
  }


  public function post() {
    return $this->hasOne('\App\MS\Models\Post', 'id', 'postid');
  }

}