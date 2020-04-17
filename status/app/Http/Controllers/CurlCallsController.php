<?php

namespace App\Http\Controllers;

use App\CurlCall;

class CurlCallsController extends Controller
{
  public function index()
  {
    return response()->json(CurlCall::all());
  }
}
