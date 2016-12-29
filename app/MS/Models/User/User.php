<?php

namespace App\MS\Models\User;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

  protected $hidden = ['id'];

  protected $fillable = ['firstname', 'lastname', 'avatar', 'following', 'followers', 'mich'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'id');
  }

}