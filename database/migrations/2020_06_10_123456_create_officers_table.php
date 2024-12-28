<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('officers', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on, with current timestamp
            $table->bigInteger('created_by')->default(-1); // created_by field
            $table->bigInteger('updated_by')->default(-1); // updated_by field
            $table->string('name', 255); // name field (not nullable)
            $table->string('name_ben', 255)->nullable(); // name_ben field (nullable)
            $table->string('designation', 64)->nullable(); // designation field (nullable)
            $table->string('department_name', 64)->nullable(); // department_name field (nullable)
            $table->string('work_phone', 255)->nullable(); // work_phone field (nullable)
            $table->string('home_phone', 255)->nullable(); // home_phone field (nullable)
            $table->string('email', 255)->nullable(); // email field (nullable)
            $table->text('external_link')->nullable(); // external_link field (nullable)
            $table->text('image_url')->nullable(); // image_url field (nullable)
            $table->integer('status')->default(0); // status field with default value of 0
            $table->integer('sorting_order')->default(9999); // sorting_order field with default value of 9999
            $table->bigInteger('department_id')->nullable(); // department_id field (nullable)
            $table->bigInteger('center_id')->nullable(); // center_id field (nullable)
            $table->string('type', 255)->default('OFFICER'); // type field with default value of 'OFFICER'
            $table->integer('type_id')->nullable(); // type_id field (nullable)

            // Optional: Add foreign key constraints if department_id and center_id reference other tables
            // $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            // $table->foreign('center_id')->references('id')->on('centers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('officers');
    }
}
