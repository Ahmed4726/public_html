<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGraduatesTable extends Migration
{
    public function up()
    {
        Schema::create('graduates', function (Blueprint $table) {
            $table->id(); // BigInt auto-incrementing ID
            $table->timestamps(0); // created_on, updated_on with CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by with default value -1
            $table->bigInteger('department_id')->nullable(); // department_id field
            $table->bigInteger('serial_no')->nullable(); // Serial number field
            $table->bigInteger('registration_no')->nullable(); // Registration number field
            $table->string('name')->nullable(); // Graduate name
            $table->string('father_name')->nullable(); // Father name
            $table->text('address')->nullable(); // Address field
            $table->string('department_name')->nullable(); // Department name field
            $table->string('comment')->nullable(); // Comment field
        });
    }

    public function down()
    {
        Schema::dropIfExists('graduates');
    }
}
