<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centers', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with default CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by field with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by field with default value -1
            $table->string('name'); // name (not nullable)
            $table->string('name_ben')->nullable(); // name_ben (nullable)
            $table->text('description')->nullable(); // description (nullable)
            $table->string('config')->nullable()->default('{"service":"Services / Facilities","download":"Download","employee":"Employee"}'); // config (nullable)
            $table->text('message_from_director')->nullable(); // message_from_director (nullable)
            $table->string('website_link')->nullable(); // website_link (nullable)
            $table->bigInteger('teacher_id')->nullable(); // teacher_id (nullable)
            $table->string('type')->default('CENTER'); // type (not nullable, default CENTER)
            $table->integer('type_id')->nullable(); // type_id (nullable)
            $table->text('ex_directors')->nullable(); // ex_directors (nullable)
            $table->string('director_name')->nullable(); // director_name (nullable)
            $table->text('director_image_url')->nullable(); // director_image_url (nullable)
            $table->integer('sorting_order')->default(0); // sorting_order with default value 0
            $table->string('director_msg_label')->nullable(); // director_msg_label (nullable)
            $table->string('slug')->nullable(); // slug (nullable)
            $table->string('director_label')->nullable()->default('Director'); // director_label with default 'Director'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('centers');
    }
}
