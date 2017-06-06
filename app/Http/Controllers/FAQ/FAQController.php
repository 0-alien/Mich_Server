<?php

namespace App\Http\Controllers\FAQ;

use App\Http\Controllers\BaseController;
use App\MS\Helpers\Mail;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

class FAQController extends BaseController {

  public function ask() {
    V::validate($this->payload, V::question);

    Mail::send('question', $this->payload, env('MAIL_USERNAME'), 'Mich Support', 'Question');

    return Responder::respond(StatusCodes::SUCCESS, 'Question Sent');
  }

}