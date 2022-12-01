<?php

namespace App\Http\Controllers\cards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardBasic extends Controller
{
  public function index()
  {
    $listTopic = DB::select('select * from table_quizzes');
    return view('content.cards.cards-basic',['listTopic'=>$listTopic]);
  }
}
