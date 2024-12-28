<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalContentsTable extends Migration
{
    public function up()
    {
        Schema::create('journal_contents', function (Blueprint $table) {
            $table->id();
            $table->timestamps(0);
            $table->bigInteger('created_by')->default(-1);
            $table->bigInteger('updated_by')->default(-1);
            $table->longText('title');
            $table->string('author')->nullable();
            $table->string('co_author')->nullable();
            $table->bigInteger('journal_id');
            $table->string('volume')->nullable();
            $table->string('path')->nullable();
            $table->integer('sorting_order')->default(9999);
            $table->string('external_link')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_contents');
    }
}
