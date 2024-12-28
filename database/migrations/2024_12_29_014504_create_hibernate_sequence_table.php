<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHibernateSequenceTable extends Migration
{
    public function up()
    {
        Schema::create('hibernate_sequence', function (Blueprint $table) {
            $table->bigInteger('next_val')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hibernate_sequence');
    }
}
