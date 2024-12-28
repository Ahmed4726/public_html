<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherCourcesTable extends Migration
{
    public function up()
    {
        Schema::create('teacher_cources', function (Blueprint $table) {
            $table->id();
            $table->timestamps(0);
            $table->bigInteger('created_by')->default(-1);
            $table->bigInteger('updated_by')->default(-1);
            $table->bigInteger('teacher_id');
            $table->string('title');
            $table->string('description')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_cources');
    }
}
