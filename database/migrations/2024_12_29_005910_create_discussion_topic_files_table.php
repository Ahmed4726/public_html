<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionTopicFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussion_topic_files', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with default CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by field with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by field with default value -1
            $table->bigInteger('discussion_topic_id'); // discussion_topic_id field (NOT NULL)
            $table->string('note', 255)->nullable(); // note field (nullable)
            $table->text('path')->nullable(); // path field (nullable)
            $table->text('name'); // name field (NOT NULL)
            $table->boolean('enabled')->default(1); // enabled field (default value 1)
            $table->boolean('header_image')->default(0); // header_image field (default value 0)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discussion_topic_files');
    }
}
