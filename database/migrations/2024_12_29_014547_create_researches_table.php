<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchesTable extends Migration
{
    public function up()
    {
        Schema::create('researches', function (Blueprint $table) {
            $table->id();
            $table->timestamps(0);
            $table->bigInteger('created_by')->default(-1);
            $table->bigInteger('updated_by')->default(-1);
            $table->bigInteger('department_id');
            $table->string('name');
            $table->string('type')->default('JOURNAL');
            $table->integer('type_id')->nullable();
            $table->text('description')->nullable();
            $table->text('website_link')->nullable();
            $table->text('message_from_editor')->nullable();
            $table->boolean('enabled')->default(1);
            $table->integer('sorting_order')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('researches');
    }
}
