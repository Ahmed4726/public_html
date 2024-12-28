<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCentersTable extends Migration
{
    public function up()
    {
        Schema::create('user_centers', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('center_id');
            $table->primary(['user_id', 'center_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_centers');
    }
}
