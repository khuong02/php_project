<?php

namespace App\Http\Controllers\topic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Quizz;
use Carbon\Carbon;

class Topic extends Controller
{
  public function index()
  {
    $listTopic = Quizz::all();
    return view('content.topic.topics',['listTopic'=>$listTopic]);
  }

  public function store(Request $request)
  {
    try{
        $name = $request->input('nametopic');

        DB::insert('insert into table_quizzes (name, created_at,updated_at) values (?, ?,?)', [$name, Carbon::now(),Carbon::now()]);

        return redirect('/topics');
    }catch(e){
        var_dump(e);
    }
  }

  public function delete($id){
    DB::update('update table_quizzes set deleted_at = ? where id = ?', [Carbon::now(),$id]);
    return redirect('/topics');
  }
}
