<?php

namespace App\MS\Services\Battle;

use App\MS\Models\Battle\Battle;
use App\MS\Models\Battle\Queue;
use App\MS\Models\Battle\Vote;
use App\MS\Models\Notification;
use App\MS\Models\Token;
use App\MS\Models\User\Credential;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;
use Illuminate\Support\Facades\DB;

class BattleService {

  public static function get($payload) {
    V::validate($payload, V::battleID);

    if (!Battle::where('id', $payload['battleID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Battle not found');
    }

    $token = Token::where('token', $payload['token'])->first();

    $battle = Battle::where('id', $payload['battleID'])->first();

    $battle->mybattle = false;
    $battle->iamhost = false;
    $battle->iamguest = false;

    if ($token->id === $battle->host) {
      $battle->mybattle = true;
      $battle->iamhost = true;
    } else if ($token->id === $battle->guest) {
      $battle->mybattle = true;
      $battle->iamguest = true;
    }

    $battle->host = [
      'id' => $battle->host,
      'username' => $battle->hostCredential->username,
      'avatar' => url('/api/media/display/' . $battle->hostCredential->user->avatar) . '?v=' . str_random(20),
      'votes' => Vote::where('battle', $battle->id)->where('host', 1)->count()
    ];
    $battle->guest = [
      'id' => $battle->guest,
      'username' => $battle->guestCredential->username,
      'avatar' => url('/api/media/display/' . $battle->guestCredential->user->avatar) . '?v=' . str_random(20),
      'votes' => Vote::where('battle', $battle->id)->where('host', 0)->count()
    ];
    unset($battle->hostCredential);
    unset($battle->guestCredential);

    return Responder::respond(StatusCodes::SUCCESS, '', $battle);
  }



  public static function getAll($payload) {
    $token = Token::where('token', $payload['token'])->first();

    $battles = Battle::whereIn('status', [0,1,3])->orderBy('id', 'desc')->get();

    foreach ($battles as $battle) {
      $battle->mybattle = false;
      $battle->iamhost = false;
      $battle->iamguest = false;

      if ($token->id === $battle->host) {
        $battle->mybattle = true;
        $battle->iamhost = true;
      } else if ($token->id === $battle->guest) {
        $battle->mybattle = true;
        $battle->iamguest = true;
      }

      $battle->host = [
        'id' => $battle->host,
        'username' => $battle->hostCredential->username,
        'avatar' => url('/api/media/display/' . $battle->hostCredential->user->avatar) . '?v=' . str_random(20),
        'votes' => Vote::where('battle', $battle->id)->where('host', 1)->count()
      ];
      $battle->guest = [
        'id' => $battle->guest,
        'username' => $battle->guestCredential->username,
        'avatar' => url('/api/media/display/' . $battle->guestCredential->user->avatar) . '?v=' . str_random(20),
        'votes' => Vote::where('battle', $battle->id)->where('host', 0)->count()
      ];
      unset($battle->hostCredential);
      unset($battle->guestCredential);
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $battles);
  }



  public static function getMine($payload) {
    $token = Token::where('token', $payload['token'])->first();

    $battles = Battle::whereIn('status', [0,1,3])->orderBy('id', 'desc')->get();

    $result = [];

    foreach ($battles as $battle) {
      if ($token->id === $battle->host || $token->id === $battle->guest) {
        $battle->mybattle = false;
        $battle->iamhost = false;
        $battle->iamguest = false;

        if ($token->id === $battle->host) {
          $battle->mybattle = true;
          $battle->iamhost = true;
        } else if ($token->id === $battle->guest) {
          $battle->mybattle = true;
          $battle->iamguest = true;
        }

        $battle->host = [
          'id' => $battle->host,
          'username' => $battle->hostCredential->username,
          'avatar' => url('/api/media/display/' . $battle->hostCredential->user->avatar) . '?v=' . str_random(20),
          'votes' => Vote::where('battle', $battle->id)->where('host', 1)->count()
        ];
        $battle->guest = [
          'id' => $battle->guest,
          'username' => $battle->guestCredential->username,
          'avatar' => url('/api/media/display/' . $battle->guestCredential->user->avatar) . '?v=' . str_random(20),
          'votes' => Vote::where('battle', $battle->id)->where('host', 0)->count()
        ];
        unset($battle->hostCredential);
        unset($battle->guestCredential);

        array_push($result, $battle);
      }
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $result);
  }



  public static function getActive($payload) {
    $token = Token::where('token', $payload['token'])->first();

    $battles = Battle::whereIn('status', [1])->orderBy('id', 'desc')->get();

    $result = [];

    foreach ($battles as $battle) {
      $battle->mybattle = false;
      $battle->iamhost = false;
      $battle->iamguest = false;

      if ($token->id === $battle->host) {
        $battle->mybattle = true;
        $battle->iamhost = true;
      } else if ($token->id === $battle->guest) {
        $battle->mybattle = true;
        $battle->iamguest = true;
      }

      $battle->host = [
        'id' => $battle->host,
        'username' => $battle->hostCredential->username,
        'avatar' => url('/api/media/display/' . $battle->hostCredential->user->avatar) . '?v=' . str_random(20),
        'votes' => Vote::where('battle', $battle->id)->where('host', 1)->count()
      ];
      $battle->guest = [
        'id' => $battle->guest,
        'username' => $battle->guestCredential->username,
        'avatar' => url('/api/media/display/' . $battle->guestCredential->user->avatar) . '?v=' . str_random(20),
        'votes' => Vote::where('battle', $battle->id)->where('host', 0)->count()
      ];
      unset($battle->hostCredential);
      unset($battle->guestCredential);

      array_push($result, $battle);
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $result);
  }



  public static function getTop($payload) {
    $token = Token::where('token', $payload['token'])->first();

    $votes = DB::table('votes')->groupBy('battle')->select(DB::raw('battle, COUNT(battle) as nBattle'))->orderBy('nBattle', 'desc')->limit(20)->get();
    $votes = $votes->pluck('battle')->toArray();
    $battles = Battle::whereIn('id', $votes)->get()->sortBy(function ($model) use ($votes) {
      return array_search($model->id, $votes);
    });

    $result = [];

    foreach ($battles as $battle) {
      $battle->mybattle = false;
      $battle->iamhost = false;
      $battle->iamguest = false;

      if ($token->id === $battle->host) {
        $battle->mybattle = true;
        $battle->iamhost = true;
      } else if ($token->id === $battle->guest) {
        $battle->mybattle = true;
        $battle->iamguest = true;
      }

      $battle->host = [
        'id' => $battle->host,
        'username' => $battle->hostCredential->username,
        'avatar' => url('/api/media/display/' . $battle->hostCredential->user->avatar) . '?v=' . str_random(20),
        'votes' => Vote::where('battle', $battle->id)->where('host', 1)->count()
      ];
      $battle->guest = [
        'id' => $battle->guest,
        'username' => $battle->guestCredential->username,
        'avatar' => url('/api/media/display/' . $battle->guestCredential->user->avatar) . '?v=' . str_random(20),
        'votes' => Vote::where('battle', $battle->id)->where('host', 0)->count()
      ];
      unset($battle->hostCredential);
      unset($battle->guestCredential);

      array_push($result, $battle);
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $result);
  }



  public static function getRandom($payload) {
    $token = Token::where('token', $payload['token'])->first();

    $battle = Battle::whereIn('status', [1])->where('host', '!=', $token->id)->where('guest', '!=', $token->id)->inRandomOrder()->first();

    if (!$battle) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Battle not found');
    }

    if ($token->id === $battle->host) {
      $battle->mybattle = true;
      $battle->iamhost = true;
    } else if ($token->id === $battle->guest) {
      $battle->mybattle = true;
      $battle->iamguest = true;
    }

    $battle->host = [
      'id' => $battle->host,
      'username' => $battle->hostCredential->username,
      'avatar' => url('/api/media/display/' . $battle->hostCredential->user->avatar) . '?v=' . str_random(20),
      'votes' => Vote::where('battle', $battle->id)->where('host', 1)->count()
    ];
    $battle->guest = [
      'id' => $battle->guest,
      'username' => $battle->guestCredential->username,
      'avatar' => url('/api/media/display/' . $battle->guestCredential->user->avatar) . '?v=' . str_random(20),
      'votes' => Vote::where('battle', $battle->id)->where('host', 0)->count()
    ];
    unset($battle->hostCredential);
    unset($battle->guestCredential);

    return Responder::respond(StatusCodes::SUCCESS, '', $battle);
  }




  public static function playRandom($payload) {
    $token = Token::where('token', $payload['token'])->first();

    if (Queue::count() && !Queue::where('user', $token->id)->exists()) {
      $hostID = Queue::first()->id;
      $guestID = $token->id;

      Queue::truncate();

      $battle = new Battle();
      $battle->host = $hostID;
      $battle->guest = $guestID;
      $battle->status = 1;
      $battle->save();

      return Responder::respond(StatusCodes::SUCCESS, 'Battle created', ['battle' => $battle->id]);
    }

    if (!Queue::where('user', $token->id)->exists()) {
      $queue = new Queue();
      $queue->user = $token->id;
      $queue->save();
    }

    return Responder::respond(StatusCodes::IN_QUEUE, 'You are in battling queue');
  }




  public static function cancelPlay($payload) {
    Queue::truncate();

    return Responder::respond(StatusCodes::SUCCESS, 'Removed from waiting queue');
  }




  public static function invite($payload) {
    V::validate($payload, V::reqUserID);

    $token = Token::where('token', $payload['token'])->first();

    if (!Credential::where('id', $payload['userID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'User not found');
    }

    if ($payload['userID'] == $token->id) {
      return Responder::respond(StatusCodes::NO_PERMISSION, 'You can not invite yourself');
    }

    if (Battle::where('host', $token->id)->where('guest', $payload['userID'])->whereIn('status', [0,1])->exists()) {
      return Responder::respond(StatusCodes::ALREADY_EXISTS, 'You have already initiated the battle');
    }


    $battle = new Battle();
    $battle->host = $token->id;
    $battle->guest = $payload['userID'];
    $battle->save();

    $notification = new Notification();
    $notification->type = 5;
    $notification->battleid = $battle->id;
    $notification->message = $battle->hostCredential->username . ' invited you to the battle';
    $notification->avatar = url('/api/media/display/' . $battle->hostCredential->user->avatar);
    $notification->userid = $battle->guest;
    $notification->save();
    $notification->send();


    return Responder::respond(StatusCodes::SUCCESS, 'Invitation sent');
  }



  public static function accept($payload) {
    V::validate($payload, V::battleID);

    $token = Token::where('token', $payload['token'])->first();

    if (!Battle::where('id', $payload['battleID'])->where('status', 0)->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Battle not found');
    }

    $battle = Battle::where('id', $payload['battleID'])->first();

    if ($battle->guest != $token->id) {
      return Responder::respond(StatusCodes::NO_PERMISSION, 'You are not invited to this battle');
    }


    $battle->status = 1;
    $battle->save();

    $notification = new Notification();
    $notification->type = 6;
    $notification->battleid = $battle->id;
    $notification->message = $battle->guestCredential->username . ' accepted your invitation';
    $notification->avatar = url('/api/media/display/' . $battle->guestCredential->user->avatar);
    $notification->userid = $battle->host;
    $notification->save();
    $notification->send();


    return Responder::respond(StatusCodes::SUCCESS, 'Battle accepted');
  }



  public static function cancel($payload) {
    V::validate($payload, V::battleID);

    $token = Token::where('token', $payload['token'])->first();

    if (!Battle::where('id', $payload['battleID'])->where('status', 0)->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Battle not found');
    }

    $battle = Battle::where('id', $payload['battleID'])->first();

    if ($battle->guest != $token->id) {
      return Responder::respond(StatusCodes::NO_PERMISSION, 'You are not invited to this battle');
    }


    $battle->status = 2;
    $battle->save();

    $notification = new Notification();
    $notification->type = 7;
    $notification->battleid = $battle->id;
    $notification->message = $battle->guestCredential->username . ' canceled your invitation';
    $notification->avatar = url('/api/media/display/' . $battle->guestCredential->user->avatar);
    $notification->userid = $battle->host;
    $notification->save();
    $notification->send();


    return Responder::respond(StatusCodes::SUCCESS, 'Battle canceled');
  }



  public static function vote($payload) {
    V::validate($payload, array_merge(V::battleID, V::host));

    $token = Token::where('token', $payload['token'])->first();

    if (!Battle::where('id', $payload['battleID'])->where('status', 1)->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Battle not found');
    }

    if (Vote::where('battle', $payload['battleID'])->where('user', $token->id)->where('host', 0)->exists() && Vote::where('battle', $payload['battleID'])->where('user', $token->id)->where('host', 1)->exists()) {
      return Responder::respond(StatusCodes::TRY_LIMIT, 'You have already voted');
    }

    $battle = Battle::where('id', $payload['battleID'])->first();

    $vote = new Vote();
    $vote->user = $token->id;
    $vote->battle = $battle->id;
    $vote->host = $payload['host'];
    $vote->save();


    return Responder::respond(StatusCodes::SUCCESS, 'You voted for ' . ($payload['host'] == 1 ? 'host' : 'guest'));
  }

}