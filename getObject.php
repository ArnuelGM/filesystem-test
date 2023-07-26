<?php

use Src\Storage;

ini_set('display_errors', 1);
ini_set('memory_limit', '1024M');

require_once __DIR__ . '/vendor/autoload.php';
require_once "config/filesystem.php";

$path =  urldecode($_SERVER['HTTP_X_REPLACED_PATH']);
$path = preg_replace( '/^\/archivos\/(.*)\//', '/${1}/', $path );
pj( $path );
exit;

try {

  //$realPath = realpath($path);

  Storage::download($path);
  exit;

  if($realPath) {
    $realPath = preg_replace('^/(archivos|forbident)', '/uploads', $realPath);
  }
  else {
    throw new \Exception('Invalid path. '.$realPath, 400);
  }
}
catch(\Exception $e) {
  $whoops = new \Whoops\Run;
  $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
  $whoops->register();
  echo $whoops->handleException($e);
}