<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\BaseController;
use App\MS\Models\Battle\Battle;
use App\MS\Models\Notification;
use Carbon\Carbon;

class CronController extends BaseController {

  public function minute() {
    $now = Carbon::now();

    $battles = Battle::where('status', 0)->get();
    foreach ($battles as $battle) {
      if ($now->diffInMinutes(Carbon::parse($battle->created_at))) {
        $battle->status = 2;
        $battle->save();

        $notification = new Notification();
        $notification->type = 7;
        $notification->battleid = $battle->id;
        $notification->message = 'invitation timed out';
        $notification->avatar = url('/api/media/display/' . $battle->guestCredential->user->avatar);
        $notification->userid = $battle->host;
        $notification->save();
        $notification->send();
      }
    }

    $battles = Battle::where('status', 1)->get();
    foreach ($battles as $battle) {
      if ($now->diffInHours(Carbon::parse($battle->created_at))) {
        $battle->status = 3;
        $battle->save();

        $notification = new Notification();
        $notification->type = 8;
        $notification->battleid = $battle->id;
        $notification->message = 'battle timed out';
        $notification->avatar = url('/api/media/display/' . $battle->guestCredential->user->avatar);
        $notification->userid = $battle->host;
        $notification->save();
        $notification->send();

        $notification = new Notification();
        $notification->type = 8;
        $notification->battleid = $battle->id;
        $notification->message = 'battle timed out';
        $notification->avatar = url('/api/media/display/' . $battle->hostCredential->user->avatar);
        $notification->userid = $battle->guest;
        $notification->save();
        $notification->send();
      }
    }
  }

}