<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_emails', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('employee_id');
            $table->foreignId('employee_type_id');
            $table->string('organization');
            $table->string('other')->nullable();
            $table->string('phone_no');
            $table->string('current_email');
            $table->string('expected_email_1');
            $table->string('expected_email_2')->nullable();
            $table->string('expected_email_3')->nullable();
            $table->string('username');
            $table->string('password');
            $table->foreignId('global_status_id');
            $table->string('completed_by')->constrained('users')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('rejected_by')->constrained('users')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('comment')->nullable();
            $table->string('updated_by')->constrained('users')->nullable();
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
        Schema::dropIfExists('employee_emails');
    }
}
