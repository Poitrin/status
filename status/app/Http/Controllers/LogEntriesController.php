<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LogEntry;

class LogEntriesController extends Controller
{
  public function create(Request $request)
  {
      $logEntry = LogEntry::create($request->all());
      return response()->json($logEntry, 201);
  }
}
