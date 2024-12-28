<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleryImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with default CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by field with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by field with default value -1
            $table->bigInteger('category_id'); // category_id field (not nullable)
            $table->text('path')->nullable(); // path field (nullable)
            $table->text('description')->nullable(); // description field (nullable)
            $table->integer('sorting_order')->default(0); // sorting_order field with default value 0
            $table->string('title', 255)->nullable(); // title field (nullable)
            $table->boolean('enabled')->default(1); // enabled field with default value 1 (tinyint(1))
            $table->string('external_link', 255)->nullable(); // external_link field (nullable)
            $table->bigInteger('department_id')->nullable(); // department_id field (nullable)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gallery_images');
    }
}
