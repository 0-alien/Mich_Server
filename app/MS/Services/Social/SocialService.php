<?php

namespace App\MS\Services\Social;

use App\MS\Models\Comment;
use App\MS\Models\Post;
use App\MS\Responder;
use App\MS\StatusCodes;
use App\MS\Validation as V;

use Illuminate\Support\Facades\Response;
use Intervention\Image\ImageManagerStatic as Image;

class SocialService {

  private static $WIDTH = 500;
  private static $FONTSIZE = 14;
  private static $BGCOLOR = '#000';
  private static $COLOR = '#FFF';
  private static $STRLEN = 55;



  public static function share($payload) {
    V::validate($payload, V::postID);

    if (!Post::where('id', $payload['postID'])->exists()) {
      return Responder::respond(StatusCodes::NOT_FOUND, 'Post not found');
    }


    $post = Post::where('id', $payload['postID'])->first();

    $image = Image::make(storage_path() . '/uploads/' . $post->image . '.jpg');
    $image->fit(self::$WIDTH);

    $commentsCanvas = self::generateCommentsCanvas($post->id);

    $canvas = Image::canvas(self::$WIDTH, $image->height() + $commentsCanvas->height(), self::$BGCOLOR);
    $canvas->insert($image, 'top');
    $canvas->insert($commentsCanvas, 'bottom');

//    $response = Response::make($canvas->encode('jpg'));
//    $response->header('Content-Type', 'image/jpg');
//    return $response;

    $name = str_random(30);
    $canvas->save(storage_path() . '/uploads/social/' . $name . '.jpg');

    return Responder::respond(StatusCodes::SUCCESS, '', ['url' => url('/api/media/display/social/' . $name)]);
  }



  private static function generateCommentsCanvas($postID) {
    $comments = Comment::where('postid', $postID)->orderBy('id', 'desc')->limit(3)->get();

    if (!$comments->count()) {
      return Image::canvas(1, 1);
    }


    $finalCanvas = null;

    foreach ($comments as $comment) {
      $avatar = Image::make(storage_path() . '/uploads/' . $comment->credential->user->avatar . '.jpg');
      $avatar->fit(32);

      $text = $comment->credential->username . ': ' . $comment->data;
      $lines = ceil(strlen($text) / self::$STRLEN);

      $height = 44 * $lines / 2;

      if ($height < 44) {
        $height = 44;
      }

      $canvas = Image::canvas(self::$WIDTH, $height, self::$BGCOLOR);
      $canvas->insert($avatar, 'top-left', 5, 5);

      $cursor = 1;

      while (strlen($text)) {
        $line = substr($text, 0, self::$STRLEN);
        $text = substr($text, self::$STRLEN);

        $canvas->text($line, 44, 20 * $cursor, function ($font) {
          $font->file('fonts/OpenSans-Regular.ttf');
          $font->color(self::$COLOR);
          $font->size(self::$FONTSIZE);
        });

        $cursor++;
      }


      if (is_null($finalCanvas)) {
        $finalCanvas = $canvas;
      } else {
        $separator = Image::canvas(self::$WIDTH, 1, '#FFF');

        $finalCanvasWrapper = Image::canvas(self::$WIDTH, $finalCanvas->height() + $height + $separator->height(), self::$BGCOLOR);
        $finalCanvasWrapper->insert($finalCanvas, 'top', 0, 0);
        $finalCanvasWrapper->insert($separator, 'top', 0, $finalCanvas->height());
        $finalCanvasWrapper->insert($canvas, 'bottom');
        $finalCanvas = $finalCanvasWrapper;
      }
    }

    return $finalCanvas;
  }

}