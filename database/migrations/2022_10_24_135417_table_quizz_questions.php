<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableQuizzQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_quizz_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('quizz_id');
            $table->unsignedInteger('difficulty_id');
            $table->string('question')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_quizz_questions');
    }
}