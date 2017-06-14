<?php

namespace App\Http\Controllers\Battle;

use App\Http\Controllers\BaseController;
use App\MS\Models\Battle\Battle;
use App\MS\Services\Battle\BattleService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BattleController extends BaseController {

  public function __construct(Request $request) {
    parent::__construct($request);

    $now = Carbon::now();

    $battles = Battle::where('status', 0)->get();
    foreach ($battles as $battle) {
      if ($now->diffInMinutes(Carbon::parse($battle->created_at))) {
        $battle->status = 2;
        $battle->save();
      }
    }

    $battles = Battle::where('status', 1)->get();
    foreach ($battles as $battle) {
      if ($now->diffInHours(Carbon::parse($battle->created_at))) {
        $battle->status = 3;
        $battle->save();
      }
    }
  }


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



  public function cancel() {
    return BattleService::cancel($this->payload);
  }

}