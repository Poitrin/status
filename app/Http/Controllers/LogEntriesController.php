<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LogEntry;

class LogEntriesController extends Controller
{
  public function create(Request $request)
  {
    $config = include('config.php');
    if ($request->bearerToken() !== $config->UPLOAD_SECRET) {
      return response('', 401); // Unauthorized
    }

    LogEntry::deleteOldEntries();
    $logEntry = LogEntry::create($request->all());
    return response()->json($logEntry, 201);
  }
}
