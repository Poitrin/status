<?php

$sites = include('sites.php');

foreach($sites as $url) {
  exec("php curl.php $url > /dev/null &");
}

?>