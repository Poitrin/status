<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogEntry extends Model
{
  protected $fillable = [
    'cpu_us',
    'current_ram',
    'total_ram',
    'hdd',
    'cpu_sy'
  ];
}
