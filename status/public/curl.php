<?php

$config = include('config.php');
$dbconn = pg_connect("host=$config->DB_HOST dbname=$config->DB_NAME user=$config->DB_USER password=$config->DB_PASSWORD");
$url = $argv[1];

$output = exec("curl -o /dev/null -w '%{url_effective};%{http_code};%{time_total}\n' -s $url");
list($url_effective, $http_code, $time_total) = explode(";", $output);
$data = array(
  'url_effective' => $url_effective,
  'http_code' => $http_code,
  'time_total' => $time_total
);

pg_query($dbconn, 'delete from curl where id not in (select id from curl order by created_at desc limit 2160)');
$response = pg_insert($dbconn, 'curl', $data);
if ($response) {
} else {
  echo "Not saved.\n";
}

pg_close($dbconn);
