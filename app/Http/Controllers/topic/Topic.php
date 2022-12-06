<?php

namespace App\Http\Controllers\topic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quizz;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class Topic extends Controller
{
    public function index()
    {
      return view('content.topic.topics');
    }

    public function getTopicList(){
      $listTopic = Quizz::all();
      return response()->json([
        'topic'=>$listTopic,
        ]);
    }

    public function store(Request $request)
    {
    try{

        $validator = Validator::make($request->all(),[
            'name'=>'required|max:100|unique:table_quizzes'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'message'=>$validator->messages(),
            ], 400);
        }

        $quiz = new Quizz();

        $quiz->name = $request->input('name');

        $quiz->save();

        return response()->json([
            'status' => 201,
            'message' => 'Topic create successfully!',
        ], 201);

    }catch(e){
        return response()->json([
            'status' => 403,
            'message' => e->message(),
        ], 403);
    }
  }

    public function edit($id){
        $topic = Quizz::find($id);
        if(isset($topic)){
            return response()->json([
                'status'=>200,
                'topic'=>$topic,
            ], 200);
        }
        return response()->json([
            'status' => 403,
            'message'=>'Không thể tìm thấy topic',
        ], 403);
    }

    public function update(Request $request,$id)
    {
        try{
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:100|unique:table_quizzes'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'message'=>$validator->messages(),
            ], 400);
        }
        $quiz = Quizz::find($id);

        $quiz->name = $request->input('name');

        $quiz->update();

        return response()->json([
            'status' => 200,
            'message' => 'Topic update successfully!',
        ], 200);
    }catch(e){
        return response()->json([
            'status' => 403,
            'message' => e->messages(),
        ], 403);
    }
    }

    public function delete(Request $request,$id){
        $topic = Quizz::find($id);
        if(isset($topic)){
            $topic->deleted_at = Carbon::now();

            $topic->update();
            return response()->json([
                'status' => 200,
                'message'=>'Delete succesfully',
            ], 200);
        }
        return response()->json([
            'status' => 403,
            'message'=>'Không thể tìm thấy topic',
        ], 403);
    }
}
