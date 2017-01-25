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


  Route::group(['prefix' => 'relation'], function () {
    Route::post('follow', 'User\RelationshipController@follow');
    Route::post('unfollow', 'User\RelationshipController@unfollow');
    Route::post('isFollowing', 'User\RelationshipController@isFollowing');
    Route::post('isFollower', 'User\RelationshipController@isFollower');
    Route::post('getFollowers', 'User\RelationshipController@getFollowers');
    Route::post('getFollowing', 'User\RelationshipController@getFollowing');
  });
});



Route::group(['prefix' => 'media'], function () {
  Route::get('display/{userID}/{image?}', 'Media\MediaController@display');
});