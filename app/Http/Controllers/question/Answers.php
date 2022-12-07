<?php

namespace App\Http\Controllers\question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Answers extends Controller
{
  public function index()
  {
    return view('content.question.answers');
  }
}
