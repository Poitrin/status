<?php
$filename = dirname(__FILE__) . '/../../vendor.zip';
$zip = new ZipArchive;
$res = $zip->open($filename);

if ($res === TRUE) {
  $path = dirname(__FILE__) . "/../..";
  $zip->extractTo($path);
  $zip->close();
  unlink($filename);
  echo 'Unzipped!';
} else {
  echo 'failed!';
}
