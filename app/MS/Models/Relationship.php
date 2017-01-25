<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model{

  protected $table = 'relationship';

  protected $fillable = ['follower', 'following'];



  public function follower() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'follower');
  }


  public function following() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'following');
  }

}