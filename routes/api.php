<?php


Route::group(['prefix' => 'auth'], function () {
  Route::post('register', 'Auth\AuthController@register');
  Route::post('login', 'Auth\AuthController@login');
});



Route::group(['prefix' => 'user', 'middleware' => 'authToken'], function () {
  Route::post('get', 'User\UserController@get');
  Route::post('update', 'User\UserController@update');
  Route::post('delete', 'User\UserController@delete');
});



Route::group(['prefix' => 'relation', 'middleware' => 'authToken'], function () {
  Route::post('follow', 'User\RelationController@follow');
  Route::post('unfollow', 'User\RelationController@unfollow');
  Route::post('following', 'User\RelationController@following');
  Route::post('follower', 'User\RelationController@follower');
});



Route::group(['prefix' => 'post', 'middleware' => 'authToken'], function () {
  Route::post('create', 'Post\PostController@create');
  Route::post('get', 'Post\PostController@get');
  Route::post('delete', 'Post\PostController@delete');
  Route::post('mich', 'Post\PostController@mich');
  Route::post('unmich', 'Post\PostController@unmich');
});



Route::group(['prefix' => 'feed', 'middleware' => 'authToken'], function () {
  Route::post('get', 'Post\FeedController@get');
});