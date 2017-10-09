<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\BaseController;
use App\MS\Services\Message\MessageService;

class MessageController extends BaseController {

  public function getMine() {
    return MessageService::getMine($this->payload);
  }



  public function create() {
    return MessageService::create($this->payload);
  }

}