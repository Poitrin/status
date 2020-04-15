<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logs</title>
  <script src="plotly-latest.min.js"></script>
</head>

<body>
  <?php
  function build_query($column)
  {
    return "select array_to_json(array_agg(row_to_json(t))) json from (select created_at x, $column y from logs where $column is not null order by created_at) t";
  }

  function execute_query($query)
  {
    return pg_query($query);
  }

  $config = include('config.php');

  // Verbindungsaufbau und Auswahl der Datenbank
  $dbconn = pg_connect("host=$config->DB_HOST dbname=$config->DB_NAME user=$config->DB_USER password=$config->DB_PASSWORD")
    or die('Verbindungsaufbau fehlgeschlagen: ' . pg_last_error());

  $current_ram_result = execute_query(build_query('current_ram'));
  $hdd_result = execute_query(build_query('hdd'));
  $cpu_us_result = execute_query(build_query('cpu_us'));
  $cpu_sy_result = execute_query(build_query('cpu_sy'));
  ?>
  <h1>CPU</h1>
  <div id="cpu-graph"></div>

  <h1>Current RAM</h1>
  <div id="current-ram-graph"></div>

  <h1>HDD</h1>
  <div id="hdd-graph"></div>

  <?php
  // Ergebnisse in HTML ausgeben
  $current_ram_json = pg_fetch_result($current_ram_result, 'json');
  $hdd_json = pg_fetch_result($hdd_result, 'json');
  $cpu_us_json = pg_fetch_result($cpu_us_result, 'json');
  $cpu_sy_json = pg_fetch_result($cpu_sy_result, 'json');

  // Speicher freigeben
  pg_free_result($current_ram_result);
  pg_free_result($hdd_result);
  pg_free_result($cpu_us_result);
  pg_free_result($cpu_sy_result);

  // Verbindung schließen
  pg_close($dbconn);
  ?>
  <script>
    function buildData(rows, options = {}) {
      return {
        x: rows.map(({
          x,
        }) => x),
        y: rows.map(({
          y
        }) => y),
        name: options.name,
        stackgroup: options.stackgroup
      };
    }

    let currentRamData = [buildData(<?php echo $current_ram_json ?>)];
    let hddData = [buildData(<?php echo $hdd_json ?>)];
    let cpuUsData = buildData(<?php echo $cpu_us_json ?>, { stackgroup: 'cpu', name: 'us' });
    let cpuSyData = buildData(<?php echo $cpu_sy_json ?>, { stackgroup: 'cpu', name: 'sy' });

    // https://plotly.com/javascript/filled-area-plots/#stacked-area-chart
    Plotly.newPlot('cpu-graph', [cpuUsData, cpuSyData]);
    Plotly.newPlot('current-ram-graph', currentRamData);
    Plotly.newPlot('hdd-graph', hddData);
  </script>
</body>

</html>