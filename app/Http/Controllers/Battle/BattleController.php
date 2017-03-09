<?php

namespace App\Http\Controllers\Battle;

use App\Http\Controllers\BaseController;
use App\MS\Services\Battle\BattleService;

class BattleController extends BaseController {

  public function invite() {
    return BattleService::invite($this->payload);
  }



  public function accept() {
    return BattleService::accept($this->payload);
  }

}