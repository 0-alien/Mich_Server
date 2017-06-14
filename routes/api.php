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

  Route::post('posts', 'User\UserController@posts');

  Route::post('report', 'User\UserController@report');
  Route::post('block', 'User\UserController@block');
  Route::post('unblock', 'User\UserController@unblock');

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

  Route::post('comments', 'Post\PostController@comments');

  Route::post('feed', 'Post\PostController@feed');

  Route::post('explore', 'Post\PostController@explore');

  Route::post('like', 'Post\PostController@like');
  Route::post('unlike', 'Post\PostController@unlike');

  Route::post('comment', 'Post\PostController@comment');
  Route::post('deleteComment', 'Post\PostController@deleteComment');

  Route::post('hide', 'Post\PostController@hide');

  Route::post('report', 'Post\PostController@reportPost');
});


Route::group(['prefix' => 'comment', 'middleware' => 'authToken'], function () {
  Route::post('like', 'Post\PostController@likeComment');
  Route::post('unlike', 'Post\PostController@unlikeComment');
  Route::post('report', 'Post\PostController@reportComment');
});



Route::group(['prefix' => 'media'], function () {
  Route::get('display/{userID}/{image?}', 'Media\MediaController@display');
});



Route::group(['prefix' => 'search', 'middleware' => 'authToken'], function () {
  Route::post('users', 'Search\SearchController@users');
});



Route::group(['prefix' => 'battle', 'middleware' => 'authToken'], function () {
  Route::post('get', 'Battle\BattleController@get');
  Route::post('getAll', 'Battle\BattleController@getAll');
  Route::post('invite', 'Battle\BattleController@invite');
  Route::post('accept', 'Battle\BattleController@accept');
  Route::post('cancel', 'Battle\BattleController@cancel');
});



Route::group(['prefix' => 'notification', 'middleware' => 'authToken'], function () {
  Route::post('get', 'Notification\NotificationController@get');
  Route::post('getAll', 'Notification\NotificationController@getAll');
  Route::post('seen', 'Notification\NotificationController@seen');
  Route::post('seenAll', 'Notification\NotificationController@seenAll');
});



Route::group(['prefix' => 'social', 'middleware' => 'authToken'], function () {
  Route::post('share', 'Social\SocialController@share');
});



Route::group(['prefix' => 'fcm', 'middleware' => 'authToken'], function () {
  Route::post('update', 'FCM\FCMController@update');
});



Route::group(['prefix' => 'faq'], function () {
  Route::post('ask', 'FAQ\FAQController@ask');
});