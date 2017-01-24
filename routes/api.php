<?php


Route::group(['prefix' => 'auth'], function () {
  Route::post('register', 'Auth\AuthController@register');
  Route::post('login', 'Auth\AuthController@login');
  Route::post('logout', ['middleware' => 'authToken', 'uses' => 'Auth\AuthController@logout']);
  Route::post('sendRecovery', 'Auth\RecoveryController@sendRecovery');
});



Route::group(['prefix' => 'user', 'middleware' => 'authToken'], function () {
  Route::post('get', 'User\UserController@get');
});