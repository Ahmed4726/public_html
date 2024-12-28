<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleryImageCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('gallery_image_categories', function (Blueprint $table) {
            $table->id(); // BigInt auto-incrementing ID
            $table->timestamps(0); // created_on, updated_on with CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by with default value -1
            $table->string('name'); // Category name field
            $table->text('description')->nullable(); // Description field
            $table->integer('sorting_order')->default(0); // Sorting order field
            $table->string('help_text')->nullable(); // Help text field
            $table->integer('max_image_size_in_kb')->default(500); // Max image size in KB
            $table->integer('width')->default(500); // Width of image
            $table->integer('height')->default(500); // Height of image
            $table->bigInteger('department_id')->nullable(); // department_id field
        });
    }

    public function down()
    {
        Schema::dropIfExists('gallery_image_categories');
    }
}
