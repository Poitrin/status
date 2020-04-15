<?php
$config = include('config.php');

// Verbindungsaufbau und Auswahl der Datenbank
$dbconn = pg_connect("host=$config->DB_HOST dbname=$config->DB_NAME user=$config->DB_USER password=$config->DB_PASSWORD")
  or die('Verbindungsaufbau fehlgeschlagen: ' . pg_last_error());

$data = $_POST;

pg_query($dbconn, 'delete from logs where id not in (select id from logs order by created_at desc limit 2160)');

$response = pg_insert($dbconn, 'logs', $data);
if ($response) {
} else {
  echo "Not saved.\n";
}

// Verbindung schließen
pg_close($dbconn);
