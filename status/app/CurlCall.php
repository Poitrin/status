<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurlCall extends Model
{
  protected $table = 'curl';

  protected $fillable = [
    /*
    'cpu_us',
    'current_ram',
    'total_ram',
    'hdd',
    'cpu_sy'
    */
  ];
}
