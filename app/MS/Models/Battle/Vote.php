<?php

namespace App\MS\Models\Battle;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model {

  protected $fillable = ['user', 'battle', 'host'];



  public function battleModel() {
    return $this->hasOne('\App\MS\Models\Battle\Battle', 'id', 'battle');
  }


  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'user');
  }

}