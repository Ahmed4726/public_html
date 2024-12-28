<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with default CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by field with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by field with default value -1
            $table->bigInteger('faculty_id'); // faculty_id field (NOT NULL)
            $table->string('name'); // name field (NOT NULL)
            $table->string('config')->default('{"program":"Academic Programs","research":"Research","facility":"Facility","link":"Important Links","file":"Download Files"}'); // config field with a default JSON value
            $table->string('name_ben')->nullable(); // name_ben field (nullable)
            $table->string('short_name', 64)->nullable(); // short_name field (nullable)
            $table->string('short_name_ben', 64)->nullable(); // short_name_ben field (nullable)
            $table->date('dob')->nullable(); // dob field (nullable)
            $table->text('image_url')->nullable(); // image_url field (nullable)
            $table->text('description')->nullable(); // description field (nullable)
            $table->text('message_from_chairman')->nullable(); // message_from_chairman field (nullable)
            $table->bigInteger('teacher_id')->nullable(); // teacher_id field (nullable)
            $table->string('contact_email')->nullable(); // contact_email field (nullable)
            $table->string('contact_mobile_number')->nullable(); // contact_mobile_number field (nullable)
            $table->string('contact_phone_number')->nullable(); // contact_phone_number field (nullable)
            $table->string('slug')->nullable(); // slug field (nullable)
            $table->string('meta_keyword')->nullable(); // meta_keyword field (nullable)
            $table->integer('sorting_order')->default(0); // sorting_order field (default value 0)
            $table->string('external_link')->nullable(); // external_link field (nullable)
            $table->boolean('image_gallery_enabled')->default(0); // image_gallery_enabled field (default value 0)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
