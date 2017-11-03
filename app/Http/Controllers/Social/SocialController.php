<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\BaseController;
use App\MS\Services\Social\SocialService;

class SocialController extends BaseController {

  public function share() {
    return SocialService::share($this->payload);
  }

}