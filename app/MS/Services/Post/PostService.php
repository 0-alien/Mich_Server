<?php

namespace App\MS\Services\Post;

use App\MS\Helpers\Media;
use App\MS\Models\Like;
use App\MS\Models\Post;
use App\MS\Models\Token;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

class PostService {

  public static function create($payload) {
    V::validate($payload, array_merge(V::title, V::image));

    $token = Token::where('token', $payload['token'])->first();

    $post = new Post();
    $post->userid = $token->id;
    $post->title = $payload['title'];
    $post->image = Media::saveImage($payload['image'], [$token->id]);
    $post->save();

    $post->image = url('/api/media/display/' . $post->image);

    return Responder::respond(StatusCodes::SUCCESS, 'Post created', $post);
  }



  public static function get($payload) {
    V::validate($payload, V::postID);

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }


    $post = Post::where('id', $payload['postID'])->first();
    $post->image = url('/api/media/display/' . $post->image);
    $post->likes = Like::where('postid', $post->id)->count();

    return Responder::respond(StatusCodes::SUCCESS, '', $post);
  }



  public static function delete($payload) {
    V::validate($payload, V::postID);

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }


    $post = Post::where('id', $payload['postID'])->first();
    $post->delete();

    return Responder::respond(StatusCodes::SUCCESS, 'Post deleted');
  }



  public static function feed($payload) {
    $token = Token::where('token', $payload['token'])->first();

    $followingIDs = [$token->id];

    $following = $token->credential->following;

    foreach ($following as $item) {
      $followingIDs[] = $item->following;
    }


    $posts = Post::whereIn('userid', $followingIDs)->orderBy('created_at', 'desc')->get();

    foreach ($posts as $post) {
      $post->image = url('/api/media/display/' . $post->image);
      $post->likes = Like::where('postid', $post->id)->count();
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $posts);
  }



  public static function like($payload) {
    V::validate($payload, V::postID);

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }


    $token = Token::where('token', $payload['token'])->first();
    $post = Post::where('id', $payload['postID'])->first();


    $like = new Like();
    $like->userid = $token->id;
    $like->postid = $post->id;
    $like->save();

    return Responder::respond(StatusCodes::SUCCESS, 'Post liked');
  }



  public static function unlike($payload) {
    V::validate($payload, V::postID);

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }


    $token = Token::where('token', $payload['token'])->first();
    $post = Post::where('id', $payload['postID'])->first();


    Like::where('userid', $token->id)->where('postid', $post->id)->delete();

    return Responder::respond(StatusCodes::SUCCESS, 'Post unliked');
  }

}