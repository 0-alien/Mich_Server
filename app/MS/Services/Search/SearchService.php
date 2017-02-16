<?php

namespace App\MS\Services\Search;

use App\MS\Models\User\Credential;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

class SearchService {

  public static function users($payload) {
    V::validate($payload, array_merge(V::term));

    $limit = 10;

    $result = Credential::where('username', 'like', $payload['term'].'%')->orderBy('id')->limit($limit)->get();

    $resultCount = $result->count();

    if ($resultCount < $limit) {
      $result = $result->merge( Credential::where('username', 'like', '%'.$payload['term'].'%')->orderBy('id')->limit($limit)->get() );
    }

    $users = [];

    foreach ($result as $credential) {
      $user = [
        'id' => $credential->id,
        'username' => $credential->username,
        'email' => $credential->email,
        'name' => $credential->user->name
      ];

      array_push($users, $user);

      $limit--;

      if ($limit === 0) {
        break;
      }
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $users);
  }

}