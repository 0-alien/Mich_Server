<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

  protected $fillable = ['userid', 'title', 'image'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'userid');
  }


  public function likes() {
    return $this->hasMany('\App\MS\Models\Like', 'postid', 'id');
  }


  public function comments() {
    return $this->hasMany('\App\MS\Models\Comment', 'postid', 'id');
  }

}