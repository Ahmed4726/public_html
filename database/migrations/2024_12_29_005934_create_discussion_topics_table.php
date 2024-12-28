<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussion_topics', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with default CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by field with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by field with default value -1
            $table->text('title'); // title field (NOT NULL)
            $table->text('details')->nullable(); // details field (nullable)
            $table->integer('event_id')->nullable(); // event_id field (nullable)
            $table->string('type', 255)->default('NEWS'); // type field with default value 'NEWS'
            $table->string('reporter', 255)->nullable(); // reporter field (nullable)
            $table->boolean('enabled')->default(1); // enabled field (default value 1)
            $table->bigInteger('department_id')->nullable(); // department_id field (nullable)
            $table->text('image_url')->nullable(); // image_url field (nullable)
            $table->string('publish_date_prev', 255)->nullable(); // publish_date_prev field (nullable)
            $table->text('external_link')->nullable(); // external_link field (nullable)
            $table->boolean('show_publish_date')->default(1); // show_publish_date field (default value 1)
            $table->boolean('featured_news')->default(0); // featured_news field (default value 0)
            $table->integer('highlight')->default(0); // highlight field (default value 0)
            $table->tinyInteger('spotlight')->default(0); // spotlight field (default value 0)
            $table->timestamp('publish_date')->default(DB::raw('CURRENT_TIMESTAMP')); // publish_date with default CURRENT_TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discussion_topics');
    }
}
