<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTeachersTable extends Migration
{
    public function up()
    {
        Schema::create('user_teachers', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('teacher_id');
            $table->primary(['user_id', 'teacher_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_teachers');
    }
}
