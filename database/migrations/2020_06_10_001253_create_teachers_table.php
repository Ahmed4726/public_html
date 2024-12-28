<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with current timestamp
            $table->bigInteger('created_by')->default(-1); // created_by field
            $table->bigInteger('updated_by')->default(-1); // updated_by field
            $table->string('name', 255); // name field (not nullable)
            $table->string('name_ben', 255)->nullable(); // name_ben field (nullable)
            $table->string('short_name', 64)->nullable(); // short_name field (nullable)
            $table->string('short_name_ben', 64)->nullable(); // short_name_ben field (nullable)
            $table->string('email', 255)->nullable(); // email field (nullable)
            $table->bigInteger('department_id'); // department_id field (not nullable)
            $table->string('designation', 64)->nullable(); // designation field (nullable)
            $table->text('image_url')->nullable(); // image_url field (nullable)
            $table->integer('status')->default(0); // status field with default value 0
            $table->longText('biography')->nullable(); // biography field (nullable)
            $table->text('research_interest')->nullable(); // research_interest field (nullable)
            $table->string('work_phone', 255)->nullable(); // work_phone field (nullable)
            $table->string('cell_phone', 255)->nullable(); // cell_phone field (nullable)
            $table->integer('sorting_order')->default(9999); // sorting_order field with default value 9999
            $table->bigInteger('tmp_user_id')->default(0); // tmp_user_id field with default value 0
            $table->string('additional_emails', 255)->nullable(); // additional_emails field (nullable)
            $table->string('slug', 255)->nullable(); // slug field (nullable)
            $table->integer('designation_id')->default(4); // designation_id field with default value 4
            $table->string('designation_text', 64)->nullable(); // designation_text field (nullable)
            $table->string('google_scholar', 255)->nullable(); // google_scholar field (nullable)
            $table->string('research_gate', 255)->nullable(); // research_gate field (nullable)
            $table->string('orcid', 255)->nullable(); // orcid field (nullable)
            $table->string('facebook', 255)->nullable(); // facebook field (nullable)
            $table->string('twitter', 255)->nullable(); // twitter field (nullable)
            $table->string('linkedin', 255)->nullable(); // linkedin field (nullable)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
