<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;

use App\MS\Services\Auth\RecoveryService;

class RecoveryController extends BaseController {

  public function sendRecovery() {
    return RecoveryService::sendRecovery($this->payload);
  }

}