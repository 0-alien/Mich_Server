<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model {

  protected $hidden = ['id'];

  protected $fillable = ['token'];

}