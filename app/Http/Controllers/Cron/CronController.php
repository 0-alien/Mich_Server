<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\BaseController;
use App\MS\Models\Battle\Battle;
use App\MS\Models\Battle\Vote;
use App\MS\Models\Notification;
use App\MS\Models\User\User;
use Carbon\Carbon;

class CronController extends BaseController {

  public function minute() {
    $now = Carbon::now();

    $battles = Battle::where('status', 0)->get();
    foreach ($battles as $battle) {
      if ($now->diffInMinutes(Carbon::parse($battle->created_at)) >= 1) {
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
      if ($now->diffInMinutes(Carbon::parse($battle->created_at)) >= 3) {
        $battle->status = 3;
        $battle->save();

        $hostVotes = Vote::where('battle', $battle->id)->where('host', 1)->count();
        $guestVotes = Vote::where('battle', $battle->id)->where('host', 0)->count();
        $hostStatus = ($hostVotes > $guestVotes ? 'win' : ($hostVotes === $guestVotes ? 'draw' : 'loss'));
        $guestStatus = ($guestVotes > $hostVotes ? 'win' : ($guestVotes === $hostVotes ? 'draw' : 'loss'));
        $host = $battle->hostCredential->user;
        $guest = $battle->guestCredential->user;
        $host->{$hostStatus} += 1;
        $host->save();
        $guest->{$guestStatus} += 1;
        $guest->save();

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