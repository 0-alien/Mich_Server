<?php

namespace App\MS\Services\Post;

use App\MS\Helpers\Censor;
use App\MS\Helpers\Media;
use App\MS\Models\Block;
use App\MS\Models\Comlike;
use App\MS\Models\Comment;
use App\MS\Models\Hiddenpost;
use App\MS\Models\Like;
use App\MS\Models\Notification;
use App\MS\Models\Post;
use App\MS\Models\Report;
use App\MS\Models\Token;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;
use Illuminate\Support\Facades\DB;

class PostService {

  public static function create($payload) {
    V::validate($payload, array_merge(V::title, V::image));

    if (!Censor::isValid($payload['title'])) {
      return Responder::respond(StatusCodes::BANNED_WORD, 'Post title includes banned word');
    }

    $token = Token::where('token', $payload['token'])->first();

    $post = new Post();
    $post->userid = $token->id;
    $post->title = $payload['title'];
    $post->image = Media::saveImage($payload['image'], [$token->id]);
    $post->save();

    $post->image = url('/api/media/display/' . $post->image) . '?v=' . str_random(20);

    return Responder::respond(StatusCodes::SUCCESS, 'Post created', $post);
  }



  public static function get($payload) {
    V::validate($payload, V::postID);

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }

    $token = Token::where('token', $payload['token'])->first();
    $post = Post::where('id', $payload['postID'])->first();
    $blockers = self::getBlockers($token->id);

    if (in_array($post->userid, $blockers)) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }


    $post->image = url('/api/media/display/' . $post->image) . '?v=' . str_random(20);
    $post->likes = Like::where('postid', $post->id)->count();
    $post->mylike = (Like::where('postid', $post->id)->where('userid', $token->id)->exists() ? 1 : 0);
    $post->ncomments = Comment::where('postid', $post->id)->count();
    $post->username = $post->credential->username;
    $post->avatar = url('/api/media/display/' . $post->credential->user->avatar) . '?v=' . str_random(20);
    unset($post->credential);

    return Responder::respond(StatusCodes::SUCCESS, '', $post);
  }



  public static function delete($payload) {
    V::validate($payload, V::postID);

    $token = Token::where('token', $payload['token'])->first();

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }


    $post = Post::where('id', $payload['postID'])->first();

    if ($post->userid != $token->id) {
      return Responder::respond(StatusCodes::NO_PERMISSION, 'You don`t have permission to delte this post');
    }

    $post->delete();

    return Responder::respond(StatusCodes::SUCCESS, 'Post deleted');
  }



  public static function comments($payload) {
    V::validate($payload, V::postID);

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }

    $token = Token::where('token', $payload['token'])->first();
    $post = Post::where('id', $payload['postID'])->first();
    $blockers = self::getBlockers($token->id);

    if (in_array($post->userid, $blockers)) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }

    $comments = $post->comments;

    foreach ($comments as $index => $comment) {
      if (in_array($comment->userid, $blockers)) {
        unset($comments[$index]);
      };

      $comment->username = $comment->credential->username;
      $comment->avatar = url('/api/media/display/' . $comment->credential->user->avatar) . '?v=' . str_random(20);
      unset($comment->credential);

      $comment->nlikes = Comlike::where('commentid', $comment->id)->count();
      $comment->mylike = 0;

      if (Comlike::where('commentid', $comment->id)->where('userid', $token->id)->exists()) {
        $comment->mylike = 1;
      }
    }

    return Responder::respond(StatusCodes::SUCCESS, '', $comments);
  }



  public static function feed($payload) {
    $token = Token::where('token', $payload['token'])->first();
    $blockers = self::getBlockers($token->id);

    $followingIDs = [$token->id];

    $following = $token->credential->following;

    foreach ($following as $item) {
      $followingIDs[] = $item->following;
    }


    $posts = Post::whereIn('userid', $followingIDs)->whereNotIn('userid', $blockers)->orderBy('created_at', 'desc')->get();

    foreach ($posts as $post) {
      $post->image = url('/api/media/display/' . $post->image) . '?v=' . str_random(20);
      $post->likes = Like::where('postid', $post->id)->count();
      $post->mylike = (Like::where('postid', $post->id)->where('userid', $token->id)->exists() ? 1 : 0);
      $post->ncomments = Comment::where('postid', $post->id)->count();
      $post->username = $post->credential->username;
      $post->avatar = url('/api/media/display/' . $post->credential->user->avatar) . '?v=' . str_random(20);
      unset($post->credential);
    }

    $posts = self::filterHiddenPosts($posts, $token->id);

    return Responder::respond(StatusCodes::SUCCESS, '', $posts);
  }



  public static function explore($payload) {
    $token = Token::where('token', $payload['token'])->first();
    $blockers = self::getBlockers($token->id);

    $likes = Like::select(DB::raw('postid, count(postid) as nlikes'))->whereNotIn('userid', $blockers)->groupBy('postid')->limit(30)->get();

    $posts = [];

    foreach ($likes as $like) {
      $post = $like->post;

      if (in_array($post->userid, $blockers)) continue;

      $post->image = url('/api/media/display/' . $post->image) . '?v=' . str_random(20);
      $post->likes = $like->nlikes;
      $post->mylike = (Like::where('postid', $post->id)->where('userid', $token->id)->exists() ? 1 : 0);
      $post->ncomments = Comment::where('postid', $post->id)->count();
      $post->username = $post->credential->username;
      $post->avatar = url('/api/media/display/' . $post->credential->user->avatar) . '?v=' . str_random(20);
      unset($post->credential);

      $posts[] = $post;
    }

    $posts = self::filterHiddenPosts($posts, $token->id);

    return Responder::respond(StatusCodes::SUCCESS, '', $posts);
  }



  public static function like($payload) {
    V::validate($payload, V::postID);

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }


    $token = Token::where('token', $payload['token'])->first();
    $post = Post::where('id', $payload['postID'])->first();

    if (!Like::where('userid', $token->id)->where('postid', $post->id)->exists()) {
      $like = new Like();
      $like->userid = $token->id;
      $like->postid = $post->id;
      $like->save();

      if ($token->id != $post->credential->id) {
        $notification = new Notification();
        $notification->type = 1;
        $notification->postid = $post->id;
        $notification->message = $like->credential->username . ' likes your post';
        $notification->avatar = url('/api/media/display/' . $like->credential->user->avatar);
        $notification->userid = $post->credential->id;
        $notification->save();
        $notification->send();
      }
    }

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



  public static function comment($payload) {
    V::validate($payload, array_merge(V::postID, V::comment));

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }

    if (!Censor::isValid($payload['comment'])) {
      return Responder::respond(StatusCodes::BANNED_WORD, 'Comment includes banned word');
    }


    $token = Token::where('token', $payload['token'])->first();
    $post = Post::where('id', $payload['postID'])->first();

    $comment = new Comment();
    $comment->userid = $token->id;
    $comment->postid = $payload['postID'];
    $comment->data = $payload['comment'];
    $comment->save();

    $comment->username = $token->credential->username;
    $comment->avatar = url('/api/media/display/' . $token->credential->user->avatar) . '?v=' . str_random(20);
    $comment->nlikes = 0;
    $comment->mylike = 0;

    if ($token->id != $post->credential->id) {
      $notification = new Notification();
      $notification->type = 2;
      $notification->postid = $post->id;
      $notification->commentid = $comment->id;
      $notification->message = $comment->username . ' commented on your post';
      $notification->avatar = url('/api/media/display/' . $token->credential->user->avatar);
      $notification->userid = $post->credential->id;
      $notification->save();
      $notification->send();
    }

    return Responder::respond(StatusCodes::SUCCESS, 'Comment added', $comment);
  }



  public static function deleteComment($payload) {
    V::validate($payload, V::commentID);

    if (!Comment::where('id', $payload['commentID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Comment not found');
    }


    $token = Token::where('token', $payload['token'])->first();
    $comment = Comment::where('id', $payload['commentID'])->first();

    if ($token->id != $comment->credential->id) {
      return Responder::respond(StatusCodes::NO_PERMISSION, 'You do not have permission on this comment');
    }

    $comment->delete();

    return Responder::respond(StatusCodes::SUCCESS, 'Comment deleted');
  }



  public static function likeComment($payload) {
    V::validate($payload, V::commentID);

    if (!Comment::where('id', $payload['commentID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Comment not found');
    }


    $token = Token::where('token', $payload['token'])->first();
    $comment = Comment::where('id', $payload['commentID'])->first();

    if (!Comlike::where('userid', $token->id)->where('commentid', $comment->id)->exists()) {
      $comlike = new Comlike();
      $comlike->userid = $token->id;
      $comlike->commentid = $comment->id;
      $comlike->save();

      if ($token->id != $comment->credential->id) {
        $notification = new Notification();
        $notification->type = 3;
        $notification->postid = $comment->postid;
        $notification->commentid = $comment->id;
        $notification->message = $token->credential->username . ' likes your comment';
        $notification->avatar = url('/api/media/display/' . $token->credential->user->avatar);
        $notification->userid = $comment->credential->id;
        $notification->save();
        $notification->send();
      }
    }

    return Responder::respond(StatusCodes::SUCCESS, 'Comment liked');
  }



  public static function unlikeComment($payload) {
    V::validate($payload, V::commentID);

    if (!Comment::where('id', $payload['commentID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Comment not found');
    }


    $token = Token::where('token', $payload['token'])->first();

    Comlike::where('userid', $token->id)->where('commentid', $payload['commentID'])->delete();

    return Responder::respond(StatusCodes::SUCCESS, 'Comment unliked');
  }



  public static function hide($payload) {
    V::validate($payload, V::postID);

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }

    $token = Token::where('token', $payload['token'])->first();

    $hiddenpost = new Hiddenpost();
    $hiddenpost->userid = $token->id;
    $hiddenpost->postid = $payload['postID'];
    $hiddenpost->save();

    return Responder::respond(StatusCodes::SUCCESS, 'Post hidden');
  }



  public static function filterHiddenPosts($posts, $userID) {
    $hiddenPosts = Hiddenpost::where('userID', $userID)->get();
    $ids = array_map(function ($item) {
      return $item['postid'];
    }, $hiddenPosts->toArray());

    $result = [];

    foreach ($posts as $post) {
      if (!in_array($post->id, $ids)) {
        $result[] = $post;
      }
    }

    return $result;
  }



  public static function reportPost($payload) {
    V::validate($payload, V::postID);

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }

    $token = Token::where('token', $payload['token'])->first();

    $report = new Report();
    $report->userid = $token->id;
    $report->type = 0;
    $report->item = $payload['postID'];
    $report->save();
    $report->notify();

    return Responder::respond(StatusCodes::SUCCESS, 'Post reported');
  }



  public static function reportComment($payload) {
    V::validate($payload, V::commentID);

    if (!Comment::where('id', $payload['commentID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Comment not found');
    }

    $token = Token::where('token', $payload['token'])->first();

    $report = new Report();
    $report->userid = $token->id;
    $report->type = 1;
    $report->item = $payload['commentID'];
    $report->save();
    $report->notify();

    return Responder::respond(StatusCodes::SUCCESS, 'Comment reported');
  }















  private static function getBlockers($id) {
    $reports = Block::where('blockid', $id)->get();

    $blockers = [];

    foreach ($reports as $report) {
      array_push($blockers, $report->userid);
    }

    return $blockers;
  }

}