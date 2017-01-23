<?php

namespace App\MS\Models\User;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model {

  protected $fillable = ['username', 'email', 'password'];



  public function user() {
    return $this->hasOne('\App\MS\Models\User\User', 'id', 'id');
  }

}