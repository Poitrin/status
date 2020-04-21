<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CurlCall;

class CurlCallsController extends Controller
{
  public function index()
  {
    return response(CurlCall::allCalls())->header('Content-Type', 'application/json');
  }

  public function create(Request $request)
  {
    $config = include('config.php');
    if ($request->bearerToken() !== $config->UPLOAD_SECRET) {
      return response('', 401); // Unauthorized
    }
    
    $sites = include('sites.php');
    foreach($sites as $url) {
      exec("php curl.php $url > /dev/null &");
    }

    return response('', 204);
  }
}
