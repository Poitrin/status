<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LogEntry extends Model
{
  protected $table = 'logs';
  public $timestamps = false;

  protected $fillable = [
    'cpu_us',
    'current_ram',
    'total_ram',
    'hdd',
    'cpu_sy'
  ];

  public static function deleteOldEntries()
  {
    return DB::delete("
      delete from logs
      where id not in (
        select id
        from logs
        order by created_at desc
        limit 2160
      )");
  }
}
