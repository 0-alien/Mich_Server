<?php

namespace App\MS\Helpers;

use App\MS\Models\Bannedword;

class Censor {

  public static function isValid($string) {
    $wordlist = Bannedword::all();

    foreach ($wordlist as $word) {
      if (strpos($string, $word->word) !== false) {
        return false;
      }
    }

    return true;
  }

}