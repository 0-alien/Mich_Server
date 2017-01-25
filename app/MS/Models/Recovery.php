<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Recovery extends Model{

  protected $table = 'recovery';

  protected $fillable = ['code', 'token', 'tries', 'match'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'id');
  }

}