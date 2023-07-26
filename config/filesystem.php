<?php

use Aws\S3\S3Client;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;

require_once __DIR__ . "/../vendor/autoload.php";

// The internal adapter
$localAdapter = new LocalFilesystemAdapter(
  // Determine root directory
  __DIR__.'/../uploads/'
);

$s3Client = new S3Client([
  'region'  => 'us-east-1',
  'version' => 'latest',
  'credentials' => [
    'key' => getenv('AWS_ACCESS_KEY_ID'),
    'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
  ],
  'endpoint' => getenv('AWS_URL') ?: null,
  'use_path_style_endpoint' => getenv('AWS_USE_PATH_STYLE_ENDPOINT') ?: false
]);

/* $r = $s3Client->getObject([
  'Bucket' => 'my-app',
  'Key' => 'cliente/storage/uploads/john snow.jpg'
]);

print_r($r['Body']->getContents());
exit; */

$s3Adapter = new AwsS3V3Adapter($s3Client,
  'my-app',
  'storage/uploads'
);

$storages = [
  'local' => new Filesystem($localAdapter),
  's3'    => new Filesystem($s3Adapter)
];
$filesystem = new MountManager($storages);

function filesystem($storage = null)
{
  global $filesystem, $storages;
  if( !is_null($storage) && !empty($storage) ) {
    if (array_key_exists($storage, $storages)) return $storages[$storage];
    else return null;
  } 
  return $filesystem;
}

