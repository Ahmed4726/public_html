<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherTeachingsTable extends Migration
{
    public function up()
    {
        Schema::create('teacher_teachings', function (Blueprint $table) {
            $table->id();
            $table->timestamps(0);
            $table->bigInteger('created_by')->default(-1);
            $table->bigInteger('updated_by')->default(-1);
            $table->bigInteger('teacher_id');
            $table->string('course_code');
            $table->text('description')->nullable();
            $table->string('course_title')->nullable();
            $table->string('semester')->nullable();
            $table->integer('status')->default(0);
            $table->integer('sorting_order')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_teachings');
    }
}
