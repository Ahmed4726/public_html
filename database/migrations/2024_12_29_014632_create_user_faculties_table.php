<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFacultiesTable extends Migration
{
    public function up()
    {
        Schema::create('user_faculties', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('faculty_id');
            $table->primary(['user_id', 'faculty_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_faculties');
    }
}