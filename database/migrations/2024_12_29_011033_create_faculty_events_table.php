<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultyEventsTable extends Migration
{
    public function up()
    {
        Schema::create('faculty_events', function (Blueprint $table) {
            $table->id(); // BigInt auto-incrementing ID
            $table->bigInteger('faculty_id'); // Faculty ID field
            $table->string('title'); // Event title
            $table->string('event_name'); // Event name
            $table->text('details')->nullable(); // Event details
            $table->string('external_link')->nullable(); // External link
            $table->string('reporter')->nullable(); // Reporter name
            $table->string('enabled')->nullable(); // Enabled field
            $table->string('publish_date')->nullable(); // Publish date
            $table->string('file')->nullable(); // File associated with event
            $table->timestamps(0); // created_at, updated_at with CURRENT_TIMESTAMP
        });
    }

    public function down()
    {
        Schema::dropIfExists('faculty_events');
    }
}
