<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherInterestsTable extends Migration
{
    public function up()
    {
        Schema::create('teacher_interests', function (Blueprint $table) {
            $table->id();
            $table->timestamps(0);
            $table->bigInteger('created_by')->default(-1);
            $table->bigInteger('updated_by')->default(-1);
            $table->bigInteger('teacher_id');
            $table->string('title');
            $table->string('summary')->nullable();
            $table->string('description')->nullable();
            $table->string('position')->nullable();
            $table->string('duration')->nullable();
            $table->string('qualifications')->nullable();
            $table->string('preferred_skills')->nullable();
            $table->string('apply_instruction')->nullable();
            $table->string('type')->default('PROJECT');
            $table->boolean('enabled')->default(1);
            $table->boolean('is_open')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_interests');
    }
}
