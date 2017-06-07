<?php

namespace App\Http\Controllers\Battle;

use App\Http\Controllers\BaseController;
use App\MS\Services\Battle\BattleService;

class BattleController extends BaseController {

  public function get() {
    return BattleService::get($this->payload);
  }



  public function getAll() {
    return BattleService::getAll($this->payload);
  }



  public function invite() {
    return BattleService::invite($this->payload);
  }



  public function accept() {
    return BattleService::accept($this->payload);
  }

}