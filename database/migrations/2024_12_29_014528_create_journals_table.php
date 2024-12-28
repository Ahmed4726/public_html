<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalsTable extends Migration
{
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->timestamps(0);
            $table->bigInteger('created_by')->default(-1);
            $table->bigInteger('updated_by')->default(-1);
            $table->string('title');
            $table->bigInteger('department_id')->nullable();
            $table->bigInteger('faculty_id')->nullable();
            $table->integer('sorting_order')->default(9999);
        });
    }

    public function down()
    {
        Schema::dropIfExists('journals');
    }
}
