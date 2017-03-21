<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\BaseController;
use App\MS\Services\Notification\NotificationService;

class NotificationController extends BaseController {

  public function get() {
    return NotificationService::get($this->payload);
  }



  public function getAll() {
    return NotificationService::getAll($this->payload);
  }



  public function seen() {
    return NotificationService::seen($this->payload);
  }



  public function seenAll() {
    return NotificationService::seenAll($this->payload);
  }

}