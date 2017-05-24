<?php

namespace App\MS\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model {

  protected $fillable = ['userid', 'type', 'item'];

}