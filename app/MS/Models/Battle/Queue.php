<?php

namespace App\MS\Models\Battle;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model {

  protected $fillable = ['user'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'user');
  }

}