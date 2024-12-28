<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdministrativeRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrative_roles', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with default CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by field with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by field with default value -1
            $table->string('type', 64); // type field (no default)
            $table->integer('type_id')->nullable(); // type_id field (nullable)
            $table->string('name', 255); // name field (no default)
            $table->text('description')->nullable(); // description field (nullable)
            $table->string('preview', 255)->default('LIST'); // preview field with default value 'LIST'
            $table->string('page_url', 255)->nullable(); // page_url field (nullable)
            $table->integer('sorting_order')->default(0); // sorting_order field with default value 0
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administrative_roles');
    }
}
