<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallsTable extends Migration
{
    public function up()
    {
        Schema::create('halls', function (Blueprint $table) {
            $table->id();
            $table->timestamps(0);
            $table->bigInteger('created_by')->default(-1);
            $table->bigInteger('updated_by')->default(-1);
            $table->double('latitude', 10, 8)->nullable();
            $table->double('longitude', 11, 8)->nullable();
            $table->string('name');
            $table->string('name_ben')->nullable();
            $table->string('short_name', 64)->nullable();
            $table->string('short_name_ben', 64)->nullable();
            $table->date('dob')->nullable();
            $table->text('image_url')->nullable();
            $table->text('description')->nullable();
            $table->text('message_from_provost')->nullable();
            $table->bigInteger('teacher_id')->nullable();
            $table->string('slug')->nullable();
            $table->integer('sorting_order')->default(0);
            $table->string('provost_label')->nullable();
            $table->string('message_from_provost_label')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('halls');
    }
}
