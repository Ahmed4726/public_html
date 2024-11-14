<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentEmailAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_email_applies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_session_id')->constrained();
            $table->foreignId('department_id');
            $table->foreignId('program_id')->constrained();
            $table->foreignId('hall_id')->nullable();
            $table->foreignId('global_status_id')->constrained();
            $table->string('registration_number');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('contact_phone');
            $table->string('existing_email');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('student_email_applies');
    }
}
