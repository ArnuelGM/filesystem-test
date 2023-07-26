<?php

namespace Src;

require_once __DIR__ . '/../config/filesystem.php';

class Storage {

  public static function put($path, $content)
  {
    filesystem('local')->write($path, $content);
    filesystem('s3')->write($path, $content);
  }

  public static function getObject($path)
  {
    // Local Strategy
    if(filesystem('local')->fileExists($path)) {
      return filesystem('local')->read($path);
    }
    // S3 STRATEGY
    else if(filesystem('s3')->fileExists($path)) {
      $data = filesystem('s3')->read($path);
      @filesystem('local')->write($path, $data);
      return $data;
    }

    throw new \Exception("File not found in: $path");
  }

  public static function upload($path, $file)
  {
    $data = file_get_contents($file['tmp_name']);
    return self::put($path, $data);
  }

  public static function download($path)
  {
    $data = self::getObject($path);
    $mimeType = filesystem('local')->mimeType($path);
    $pathInfo = pathinfo($path);
    self::setHeaders($mimeType, $pathInfo, filesystem('local')->fileSize($path));
    echo $data;
    exit;
  }

  public static function setHeaders($mimeType, $pathInfo = null, $fileSize = null)
  {
    header("Content-Type: $mimeType");
    header('Content-Disposition: attachment; filename="' . $pathInfo['basename'] .  '"');
    header('Content-Length: ' . $fileSize);
  }

}