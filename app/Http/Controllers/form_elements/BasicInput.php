<?php

namespace App\Http\Controllers\form_elements;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BasicInput extends Controller
{
  public function index()
  {
    $listUser = DB::select('select * from users');
    return view('content.form-elements.forms-basic-inputs',['listUser'=>$listUser]);
  }
}
