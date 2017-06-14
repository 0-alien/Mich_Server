<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\BaseController;
use App\MS\Models\Battle\Battle;
use Carbon\Carbon;

class CronController extends BaseController {

  public function minute() {
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

}