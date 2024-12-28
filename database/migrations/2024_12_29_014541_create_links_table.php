<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->timestamps(0);
            $table->bigInteger('created_by')->default(-1);
            $table->bigInteger('updated_by')->default(-1);
            $table->string('type', 64)->default('OTHER');
            $table->integer('type_id')->nullable();
            $table->string('label')->nullable();
            $table->text('link_url')->nullable();
            $table->string('target', 64)->nullable();
            $table->string('css_class', 64)->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->boolean('enabled')->default(1);
            $table->integer('sorting_order')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('links');
    }
}
