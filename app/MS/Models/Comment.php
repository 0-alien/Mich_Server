<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

  protected $fillable = ['userid', 'postid', 'reply'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'userid');
  }


  public function post() {
    return $this->hasOne('\App\MS\Models\Post', 'id', 'postid');
  }


  public function parent() {
    return $this->hasOne('\App\MS\Models\Comment', 'id', 'reply');
  }

}