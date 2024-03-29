<?php

namespace App\MS\Services\Search;

use App\MS\Models\Block;
use App\MS\Models\Report;
use App\MS\Models\Token;
use App\MS\Models\User\Credential;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

class SearchService {

  public static function users($payload) {
    V::validate($payload, array_merge(V::term));

    $token = Token::where('token', $payload['token'])->first();

    $limit = 10;

    $blockers = self::getBlockers($token->id);

    $result = Credential::where('username', 'like', $payload['term'].'%')->whereNotIn('id', $blockers)->orderBy('id')->limit($limit)->get();

    $resultCount = $result->count();

    if ($resultCount < $limit) {
      $result = $result->merge( Credential::where('username', 'like', '%'.$payload['term'].'%')->whereNotIn('id', $blockers)->orderBy('id')->limit($limit)->get() );
    }

    $users = [];

    foreach ($result as $credential) {
      $user = [
        'id' => $credential->id,
        'username' => $credential->username,
        'email' => $credential->email,
        'name' => $credential->user->name,
        'avatar' => url('/api/media/display/' . $credential->user->avatar),
      ];

      array_push($users, $user);

      $limit--;

      if ($limit === 0) {
        break;
      }
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $users);
  }



  private static function getBlockers($id) {
    $reports = Block::where('blockid', $id)->get();

    $blockers = [];

    foreach ($reports as $report) {
      array_push($blockers, $report->userid);
    }

    return $blockers;
  }

}