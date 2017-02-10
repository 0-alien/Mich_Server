<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\BaseController;

use App\MS\Helpers\Media;

class MediaController extends BaseController {

  public function display($userID, $image = null) {
    $image = (is_null($image) ? '' : '/'.$image);
    return Media::displayImage($userID . $image);
  }

}