<?php

namespace App\MS\Helpers;

use Illuminate\Support\Facades\File;

use Intervention\Image\ImageManagerStatic as Image;

class Media {

  private static function createDirectories($folders) {
    $fullPath = storage_path() . '/uploads/';
    $path = '';

    foreach ($folders as $folder) {
      if (!File::exists($fullPath . $folder)) {
        File::makeDirectory($fullPath . $folder, 0777);
      }

      $fullPath .= $folder . '/';
      $path .= $folder . '/';
    }

    return $path;
  }


  public static function saveImage($base64, $folders, $name = null) {
    $img = Image::make(base64_decode($base64))->encode('jpg');

    if ($img->width() > 1024) {
      $img->resize(1024, null, function ($constraint) {
        $constraint->aspectRatio();
      });
    }

    $path = self::createDirectories($folders);

    if (is_null($name)) {
      $name = str_random(20);
      while (File::exists(storage_path() . '/uploads/' . $path . $name . 'jpg')) {
        $name = str_random(20);
      }
    }

    $path .= $name;

    $img->save(storage_path() . '/uploads/' . $path . '.jpg', 80);

    return $path;
  }



  public static function displayImage($path) {
    return Image::make(storage_path() . '/uploads/' . $path . '.jpg')->response('jpg');
  }

}