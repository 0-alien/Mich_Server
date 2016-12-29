<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model {

  protected $hidden = ['id'];

  protected $fillable = ['token'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'id');
  }

}