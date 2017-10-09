<?php

namespace App\Http\Controllers\Battle;

use App\Http\Controllers\BaseController;
use App\MS\Services\MessageService;

class MessageController extends BaseController {

  public function getMine() {
    return MessageService::getMine($this->payload);
  }



  public function create() {
    return MessageService::create($this->payload);
  }

}