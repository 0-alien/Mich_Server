<?php


Route::group(['prefix' => 'auth'], function () {
  Route::post('register', 'Auth\AuthController@register');
  Route::post('login', 'Auth\AuthController@login');
  Route::post('logout', ['middleware' => 'authToken', 'uses' => 'Auth\AuthController@logout']);
  Route::post('sendRecovery', 'Auth\RecoveryController@sendRecovery');
  Route::post('checkCode', 'Auth\RecoveryController@checkCode');
  Route::post('recover', 'Auth\RecoveryController@recover');
});



Route::group(['prefix' => 'user', 'middleware' => 'authToken'], function () {
  Route::post('get', 'User\UserController@get');
  Route::post('update', 'User\UserController@update');
  Route::post('delete', 'User\UserController@delete');
  Route::post('changePassword', 'User\UserController@changePassword');

  Route::group(['prefix' => 'relation'], function () {
    Route::post('follow', 'User\RelationshipController@follow');
    Route::post('unfollow', 'User\RelationshipController@unfollow');
    Route::post('isFollowing', 'User\RelationshipController@isFollowing');
    Route::post('isFollower', 'User\RelationshipController@isFollower');
    Route::post('getFollowers', 'User\RelationshipController@getFollowers');
    Route::post('getFollowing', 'User\RelationshipController@getFollowing');
  });
});



Route::group(['prefix' => 'post', 'middleware' => 'authToken'], function () {
  Route::post('create', 'Post\PostController@create');
  Route::post('get', 'Post\PostController@get');
  Route::post('delete', 'Post\PostController@delete');

  Route::post('feed', 'Post\PostController@feed');

  Route::post('like', 'Post\PostController@like');
  Route::post('unlike', 'Post\PostController@unlike');

  Route::post('comment', 'Post\PostController@comment');
});



Route::group(['prefix' => 'media'], function () {
  Route::get('display/{userID}/{image?}', 'Media\MediaController@display');
});



Route::group(['prefix' => 'search', 'middleware' => 'authToken'], function () {
  Route::post('users', 'Search\SearchController@users');
});