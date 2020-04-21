# Status
This Lumen app is currently hosted on a shared web hosting service by Hetzner. My current web space has 10 GB of HDD, 192 MB RAM, an execution limit of up to 120 seconds, a PostgreSQL 9 database, and allows no cron jobs.

I wanted to find out whether a Lumen app runs smoothly on a shared web hosting service, and how the app can easily be deployed via FTP (because that's the only option I have) and GitHub.

## Architecture
### VPS health condition → Status app
My Linux VPS (currently hosting a Rails, Grails and Phoenix app) executes the script `public/stats.sh` every minute via a cron job.
This script sends the following data to the shared web hosting service (`POST /logs`):
- `cpu_us`
- `cpu_sy`
- `current_ram`
- `total_ram`
- `hdd`
The Lumen app writes the data into the table `logs`.

### Google Apps Script ping → Status app curl call → Sites to check
At the same time, a Google Apps Script runs every 5 minutes and calls `GET /ping-servers.php`. This script pings all the sites in `public/sites.php`, via `curl`, and writes the data into the table `curl`.

### Status app: logs.php
A dashboard displays all the relevant data.

## Installation
### Create config files
- Create file `public/config.php` with your DB config:
  ```php
  <?php

  return (object) array(
    'DB_NAME' => '...',
    'DB_USER' => '...',
    'DB_PASSWORD' => '...',
    'DB_HOST' => '...'
  );
  ```
- Create file `public/sites.php` with your sites to check:
  ```php
  <?php

  return array(
    "https://...",
    ...
  );
  ```
- Copy `.env.example` to `.env` and update values.

### Install dependencies
To install the defined dependencies for your project, run the install command.
```shell
php composer.phar install
```

### Create database tables
- Execute the following DDL commands:
  ```sql
  CREATE TABLE public.alias (
    id serial NOT NULL,
    created_at timestamp NULL DEFAULT now(),
    url varchar NOT NULL,
    alias varchar NOT NULL,
    CONSTRAINT alias_pkey PRIMARY KEY (id)
  );

  CREATE TABLE public.logs (
    id serial NOT NULL,
    created_at timestamp NULL DEFAULT now(),
    cpu_us float4 NOT NULL,
    current_ram int4 NOT NULL,
    total_ram int4 NOT NULL,
    hdd float4 NOT NULL,
    cpu_sy float4 NULL,
    CONSTRAINT logs_pkey PRIMARY KEY (id)
  );

  CREATE TABLE public.curl (
    id serial NOT NULL,
    created_at timestamp NULL DEFAULT now(),
    url_effective varchar NULL,
    http_code int4 NULL,
    time_total float4 NULL,
    CONSTRAINT curl_pkey PRIMARY KEY (id)
  );
  ```
- Add your sites into the `alias` table.

### Start server
- Create a `.composer` directory (it's defined as a volume in `docker-compose.yml`)
- Start the server:
  ```
  docker-compose up
  ```

### Misc.
In case you want to install `lumen-installer`:
```shell
docker-compose run --rm web bash
# In Container:
composer global require "laravel/lumen-installer"
```

## Deployment
- Make sure you have installed all dependencies via `composer`.
- Make sure your `.env` file contains the correct configuration (`APP_DEBUG`, database connection, etc.).
- Upload all your files via FTP (or whatever you like).
  - Tip: FTP is quite slow for all the files in `vendor`. ZIP all your files, upload the archive via FTP, connect to your server via SSH and extract the archive.
  - If you have no SSH access, you can try writing a small PHP helper script that uses `exec(...)` to unzip the archive.

### Deployment via GitHub Actions
This repo has a GitHub Actions workflow for continuous deployment. It uses [`FTP-Deploy-Action`](https://github.com/SamKirkland/FTP-Deploy-Action), which uses [`git-ftp`](https://github.com/git-ftp/git-ftp) behind the scenes. For each new commit, the changed files are calculated and then uploaded via FTP.

## License

The app is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
