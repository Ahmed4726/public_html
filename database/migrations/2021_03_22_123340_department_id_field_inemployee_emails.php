<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DepartmentIdFieldInemployeeEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_emails', function (Blueprint $table) {
            $table->dropColumn('organization');
            $table->foreignId('department_id')->nullable()->after('employee_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_emails', function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->string('organization')->after('employee_type_id');
        });
    }
}
