<?php

namespace App\MS\Models\User;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model {

  protected $fillable = ['username', 'email', 'password'];



  public function user() {
    return $this->hasOne('\App\MS\Models\User\User', 'id', 'id');
  }


  public function followers() {
    return $this->hasMany('\App\MS\Models\Relationship', 'following', 'id');
  }


  public function following() {
    return $this->hasMany('\App\MS\Models\Relationship', 'follower', 'id');
  }


  public function posts() {
    return $this->hasMany('\App\MS\Models\Post', 'userid', 'id');
  }


  public function likes() {
    return $this->hasMany('\App\MS\Models\Like', 'userid', 'id');
  }

}