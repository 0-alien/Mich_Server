<?php

namespace App\MS\Models\User;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model {

  protected $hidden = ['id', 'salt'];

  protected $fillable = ['username', 'email', 'password'];



  public function user() {
    return $this->hasOne('\App\MS\Models\User\User', 'id', 'id');
  }


  public function token() {
    return $this->hasOne('\App\MS\Models\Token', 'id', 'id');
  }


  public function posts() {
    return $this->hasMany('\App\MS\Models\Post\Post', 'user_id', 'id');
  }

}