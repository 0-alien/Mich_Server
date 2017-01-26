<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

  protected $fillable = ['userid', 'title', 'image'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'id', 'userid');
  }

}