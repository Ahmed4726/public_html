<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educations', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with default CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by field with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by field with default value -1
            $table->bigInteger('teacher_id'); // teacher_id field (NOT NULL)
            $table->string('degree', 255); // degree field (NOT NULL)
            $table->integer('from_year')->nullable(); // from_year field (nullable)
            $table->integer('to_year')->nullable(); // to_year field (nullable)
            $table->string('field', 255)->nullable(); // field of study (nullable)
            $table->string('duration', 64)->nullable(); // duration of the education (nullable)
            $table->string('institute', 255)->nullable(); // institute name (nullable)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('educations');
    }
}
