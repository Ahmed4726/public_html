<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_educations', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on, with current timestamp
            $table->bigInteger('created_by')->default(-1); // created_by field
            $table->bigInteger('updated_by')->default(-1); // updated_by field
            $table->bigInteger('teacher_id')->nullable(false); // teacher_id field
            $table->string('institute', 255)->nullable(); // institute field
            $table->text('description')->nullable(); // description field
            $table->string('period', 255)->nullable(); // period field
            $table->integer('status')->default(0); // status field
            $table->integer('sorting_order')->default(0); // sorting_order field
            $table->bigInteger('original_teacher_id')->default(0); // original_teacher_id field

            // Index for teacher_id for better performance
            $table->index('teacher_id'); 

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_educations');
    }
}
