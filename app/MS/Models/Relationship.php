<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model{

  protected $table = 'relationship';

  protected $fillable = ['follower', 'following'];



  public function followerUser() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'follower');
  }


  public function followingUser() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'following');
  }

}