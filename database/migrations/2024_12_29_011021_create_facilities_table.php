<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitiesTable extends Migration
{
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id(); // BigInt auto-incrementing ID
            $table->timestamps(0); // created_on, updated_on with CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by with default value -1
            $table->bigInteger('department_id')->nullable(); // department_id field
            $table->string('name'); // Name field
            $table->string('name_ben')->nullable(); // Bengali name field
            $table->text('description')->nullable(); // Description field
            $table->text('contact')->nullable(); // Contact field
            $table->text('image_url')->nullable(); // Image URL field
            $table->bigInteger('center_id')->nullable(); // center_id field
            $table->tinyInteger('enabled')->default(1); // Enabled field with default value 1
            $table->string('external_link')->nullable(); // External link field
            $table->integer('sorting_order')->default(0); // Sorting order field
        });
    }

    public function down()
    {
        Schema::dropIfExists('facilities');
    }
}
