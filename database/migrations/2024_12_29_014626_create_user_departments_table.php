<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::create('user_departments', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('department_id');
            $table->primary(['user_id', 'department_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_departments');
    }
}
