<?php

namespace App\Http\Controllers\FCM;

use App\Http\Controllers\BaseController;

use App\MS\Services\FCM\FCMService;

class FCMController extends BaseController {

  public function update() {
    return FCMService::update($this->payload);
  }

}