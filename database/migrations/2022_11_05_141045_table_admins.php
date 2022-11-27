<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableAdmins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->nullable(true);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar')->default('https://res.cloudinary.com/didqd2uyc/image/upload/v1668469798/hluc0oca3d2kke3ifcvr.jpg');
            $table->softDeletes();
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
        Schema::dropIfExists('table_admins');
    }
}
