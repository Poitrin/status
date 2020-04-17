<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CurlCall extends Model
{
  protected $table = 'curl';

  public static function allCalls()
  {
    $file_path = __DIR__ . '/curl_calls_to_json.sql';
    $row = DB::selectOne(file_get_contents($file_path));
    return $row->json;
  }
}
