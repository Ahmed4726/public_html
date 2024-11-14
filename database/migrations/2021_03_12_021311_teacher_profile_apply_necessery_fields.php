<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TeacherProfileApplyNecesseryFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('employee_id')->nullable()->after('name');
            $table->string('email')->nullable()->change();
            $table->foreignId('employee_email_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('employee_id');
            $table->string('email')->change();
            $table->dropForeign(['employee_email_id']);
        });
    }
}
