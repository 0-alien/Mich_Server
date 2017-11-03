<?php

namespace App\MS\Helpers;

use App\MS\Models\Bannedword;

class Censor {

  public static function isValid($string) {
    $string = explode(' ', $string);
    $wordlist = Bannedword::all();

    foreach ($wordlist as $word) {
      if (in_array($word->word, $string)) {
        return false;
      }
    }

    return true;
  }

}