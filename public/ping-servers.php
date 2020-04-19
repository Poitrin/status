<?php

$config = include('config.php');
$token = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  return http_response_code(405); // Method not allowed
}

if ($token !== $config->UPLOAD_SECRET) {
  return http_response_code(401); // Unauthorized
}

$sites = include('sites.php');
foreach($sites as $url) {
  exec("php curl.php $url > /dev/null &");
}
