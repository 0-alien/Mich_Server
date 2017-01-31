<?php

namespace App\MS\Models\User;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

  protected $fillable = ['name', 'avatar'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'id');
  }

}