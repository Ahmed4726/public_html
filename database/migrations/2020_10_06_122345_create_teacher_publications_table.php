<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherPublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_publications', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with current timestamp
            $table->bigInteger('created_by')->default(-1); // created_by field
            $table->bigInteger('updated_by')->default(-1); // updated_by field
            $table->bigInteger('teacher_id'); // teacher_id field (not nullable)
            $table->text('name')->nullable(); // name field (nullable)
            $table->string('author_name', 255)->nullable(); // author_name field (nullable)
            $table->string('publication_year', 255)->nullable(); // publication_year field (nullable)
            $table->string('journal_name', 255)->nullable(); // journal_name field (nullable)
            $table->string('conference_location', 255)->nullable(); // conference_location field (nullable)
            $table->string('volume', 255)->nullable(); // volume field (nullable)
            $table->string('issue', 255)->nullable(); // issue field (nullable)
            $table->string('page', 255)->nullable(); // page field (nullable)
            $table->text('url')->nullable(); // url field (nullable)
            $table->string('url2', 255)->nullable(); // url2 field (nullable)
            $table->mediumText('description')->nullable(); // description field (nullable)
            $table->string('type', 64)->default('Others'); // type field with default value 'Others'
            $table->text('file')->nullable(); // file field (nullable)
            $table->integer('status')->default(0); // status field with default value 0
            $table->integer('sorting_order')->default(0); // sorting_order field with default value 0
            $table->string('teacher_publication_type', 255)->default('OTHER'); // teacher_publication_type field with default value 'OTHER'
            $table->integer('teacher_publication_type_id')->nullable(); // teacher_publication_type_id field (nullable)
            $table->bigInteger('original_teacher_id')->default(0); // original_teacher_id field with default value 0
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_publications');
    }
}
