<?php


Route::post('auth/register', 'Auth\AuthController@register');
Route::post('auth/login', 'Auth\AuthController@authenticate');
Route::post('auth/recovery', 'Auth\AuthController@recovery');