<?php

namespace App\MS\Models\Battle;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model {

  protected $fillable = ['battle', 'host'];



  public function battleModel() {
    return $this->hasOne('\App\MS\Models\Battle\Battle', 'id', 'battle');
  }

}