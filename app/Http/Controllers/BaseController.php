<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController {

  protected $payload;


  public function __construct(Request $request) {
    $this->payload = json_decode($request->getContent(), true);
  }

}