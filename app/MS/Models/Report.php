<?php

namespace App\MS\Models;

use App\MS\Helpers\Mail;
use Illuminate\Database\Eloquent\Model;

class Report extends Model {

  protected $fillable = ['userid', 'type', 'item'];



  public function notify() {
    $types = ['Post', 'Comment', 'User'];
    $type = $types[$this->type];
    $message = 'Reporter: ' . $this->userid . '; Type: ' . $type . '; ItemID: ' . $this->item;
    Mail::send('report', ['report' => $message], 'znatr10@freeuni.edu.ge', 'Zura Natroshvili', 'Report');
  }

}