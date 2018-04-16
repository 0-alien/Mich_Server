<?php

namespace App\MS\Models\User;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

  protected $fillable = ['name', 'dateofbirth', 'location', 'avatar', 'win', 'draw', 'loss', 'bio'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'id');
  }

}