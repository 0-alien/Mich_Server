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


  public static function saveImage($base64, $folders, $name) {
    $img = Image::make(base64_decode($base64))->resize(200, 200)->encode('jpg');

    $path = self::createDirectories($folders) . $name;

    $img->save(storage_path() . '/uploads/' . $path . '.jpg', 80);

    return $path;
  }



  public static function displayImage($path) {
    return Image::make(storage_path() . '/uploads/' . $path . '.jpg')->response('jpg');
  }

}