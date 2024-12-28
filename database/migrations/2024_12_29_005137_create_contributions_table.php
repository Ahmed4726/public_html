<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with default CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by field with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by field with default value -1
            $table->bigInteger('teacher_id')->default(0); // teacher_id field (non-nullable, default 0)
            $table->string('title'); // title (not nullable)
            $table->string('description')->nullable(); // description (nullable)
            $table->string('sponsored_by')->nullable(); // sponsored_by (nullable)
            $table->string('investor')->nullable(); // investor (nullable)
            $table->string('duration')->nullable(); // duration (nullable)
            $table->string('bibliography')->nullable(); // bibliography (nullable)
            $table->string('link')->nullable(); // link (nullable)
            $table->integer('year')->nullable(); // year (nullable)
            $table->string('type')->default('PUBLICATION'); // type (not nullable, default PUBLICATION)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contributions');
    }
}
