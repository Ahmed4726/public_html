<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultiesTable extends Migration
{
    public function up()
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id(); // BigInt auto-incrementing ID
            $table->timestamps(0); // created_on, updated_on with CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by with default value -1
            $table->string('name'); // Name field
            $table->string('name_ben')->nullable(); // Bengali name field
            $table->text('description')->nullable(); // Description field
            $table->text('message_from_dean')->nullable(); // Message from dean
            $table->bigInteger('teacher_id')->nullable(); // teacher_id field
            $table->string('type')->default('FACULTY'); // Faculty type with default 'FACULTY'
            $table->string('slug')->nullable(); // Slug field
            $table->integer('sorting_order')->default(0); // Sorting order field
            $table->string('email')->nullable(); // Email field
            $table->string('fax')->nullable(); // Fax field
            $table->string('fabx')->nullable(); // FABX field
            $table->string('mobile_number')->nullable(); // Mobile number field
            $table->string('phone_number')->nullable(); // Phone number field
        });
    }

    public function down()
    {
        Schema::dropIfExists('faculties');
    }
}
