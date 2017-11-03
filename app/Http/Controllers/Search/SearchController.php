<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\BaseController;
use App\MS\Services\Search\SearchService;

class SearchController extends BaseController {

  public function users() {
    return SearchService::users($this->payload);
  }

}