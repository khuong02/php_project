<?php

namespace App\Http\Controllers\tables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Basic extends Controller
{
  public function index()
  {
    $listAdmin = DB::select('select * from table_admins');
    return view('content.tables.tables-basic',['listAdmin'=>$listAdmin]);
  }
}
