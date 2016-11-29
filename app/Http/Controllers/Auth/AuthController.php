<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\MS\Services\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

  public function authenticate(Request $request)
  {
    return AuthService::authenticate($request);
  }

}