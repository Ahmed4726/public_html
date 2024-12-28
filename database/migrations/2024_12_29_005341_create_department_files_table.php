<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_files', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with default CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by field with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by field with default value -1
            $table->bigInteger('department_id')->nullable(false); // department_id (NOT NULL)
            $table->text('name'); // name field (NOT NULL)
            $table->text('path')->nullable(); // path field (nullable)
            $table->text('description')->nullable(); // description field (nullable)
            $table->string('type')->nullable(); // type field (nullable)
            $table->boolean('listing_enabled')->default(1); // listing_enabled (default value 1)
            $table->integer('sorting_order')->default(0); // sorting_order (default value 0)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department_files');
    }
}
