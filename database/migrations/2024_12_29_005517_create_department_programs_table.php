<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_programs', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with default CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by field with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by field with default value -1
            $table->bigInteger('department_id')->nullable(); // department_id field (nullable)
            $table->string('name'); // name field (NOT NULL)
            $table->text('description')->nullable(); // description field (nullable)
            $table->bigInteger('center_id')->nullable(); // center_id field (nullable)
            $table->string('type')->default('PROGRAM'); // type field with default value 'PROGRAM'
            $table->integer('type_id')->nullable(); // type_id field (nullable)
            $table->text('path')->nullable(); // path field (nullable)
            $table->boolean('enabled')->default(1); // enabled field (default value 1)
            $table->integer('sorting_order')->default(0); // sorting_order field (default value 0)
            $table->string('external_link')->nullable(); // external_link field (nullable)
            $table->bigInteger('hall_id')->nullable(); // hall_id field (nullable)
            $table->string('slug')->nullable(); // slug field (nullable)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department_programs');
    }
}
