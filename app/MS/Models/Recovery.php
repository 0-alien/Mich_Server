<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Recovery extends Model{

  protected $table = 'recovery';

  protected $fillable = ['code', 'token'];

}