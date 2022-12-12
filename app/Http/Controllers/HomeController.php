<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    //
    public function index(Request $request)
    {

        $totalUser = DB::select("select count(*) as 'count' from users");
        $totalTopic = DB::select("select count(*) as 'count' from table_quizzes");
        $totalQuestion = DB::select("select count(*) as 'count' from table_quizz_questions");
        return view('content.dashboard.dashboards-analytics', ['totalUser' => $totalUser[0]->count, 'totalTopic' => $totalTopic[0]->count, 'totalQuestion' => $totalQuestion[0]->count]);
    }
}
