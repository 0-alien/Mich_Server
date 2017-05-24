<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model {

  protected $fillable = ['token', 'fcmrt'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'id');
  }

}