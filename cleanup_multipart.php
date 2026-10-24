<?php

/** Backend-neutral maintenance entry point; schedule from cron as before. */
require_once __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('UTC');

use S3Gateway\Config;
use S3Gateway\Storage\MultipartStorage;
use S3Gateway\Storage\StorageFactory;

$storage = StorageFactory::create();
if (!$storage instanceof MultipartStorage) {
    echo "Selected storage backend has no multipart uploads to clean up.\n";
    exit(0);
}
$result = $storage->cleanupExpiredMultipartUploads(time() - Config::multipartUploadTtl());
echo 'Cleanup complete: removed ' . $result['removed'] . ' upload(s), freed ' . $result['bytes'] . " bytes\n";
