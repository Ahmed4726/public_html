<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHallsTable extends Migration
{
    public function up()
    {
        Schema::create('user_halls', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('hall_id');
            $table->primary(['user_id', 'hall_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_halls');
    }
}
