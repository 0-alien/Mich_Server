<?php

namespace App\Http\Controllers\Battle;

use App\Http\Controllers\BaseController;
use App\MS\Models\Battle\Battle;
use App\MS\Services\Battle\BattleService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BattleController extends BaseController {

  public function get() {
    return BattleService::get($this->payload);
  }



  public function getAll() {
    return BattleService::getAll($this->payload);
  }



  public function getMine() {
    return BattleService::getMine($this->payload);
  }



  public function getActive() {
    return BattleService::getActive($this->payload);
  }



  public function getTop() {
    return BattleService::getTop($this->payload);
  }



  public function getRandom() {
    return BattleService::getRandom($this->payload);
  }



  public function invite() {
    return BattleService::invite($this->payload);
  }



  public function accept() {
    return BattleService::accept($this->payload);
  }



  public function cancel() {
    return BattleService::cancel($this->payload);
  }



  public function vote() {
    return BattleService::vote($this->payload);
  }

}