<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCenterFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('center_files', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with default CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by field with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by field with default value -1
            $table->bigInteger('center_id'); // center_id (not nullable)
            $table->text('name'); // name (not nullable)
            $table->string('note', 255)->nullable(); // note (nullable)
            $table->text('path')->nullable(); // path (nullable)
            $table->string('external_link', 255)->nullable(); // external_link (nullable)
            $table->boolean('listing_enabled')->default(true); // listing_enabled with default value 1
            $table->integer('sorting_order')->default(0); // sorting_order with default value 0
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('center_files');
    }
}
