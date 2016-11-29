<?php


Route::post('auth', 'Auth\AuthController@authenticate');


Route::group(['middleware' => []], function () {



});