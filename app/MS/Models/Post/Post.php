<?php

namespace App\MS\Models\Post;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

  protected $hidden = ['id'];

  protected $fillable = ['user_id', 'title', 'image', 'mich'];



  public function credential() {
    return $this->hasOne('\App\MS\Models\User\Credential', 'user_id', 'id');
  }

}