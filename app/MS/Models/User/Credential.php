<?php

namespace App\MS\Models\User;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{

  protected $hidden = ['id', 'salt'];

  protected $fillable = ['email', 'password'];

}