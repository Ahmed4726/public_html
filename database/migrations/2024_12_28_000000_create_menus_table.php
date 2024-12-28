<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('created_on')->useCurrent();
            $table->timestamp('updated_on')->useCurrent()->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->bigInteger('created_by')->default(-1);
            $table->bigInteger('updated_by')->default(-1);
            $table->bigInteger('root_id')->nullable();
            $table->string('display_text', 255);
            $table->string('link', 255)->nullable();
            $table->string('type', 64)->default('MENU');
            $table->boolean('enabled')->default(true);
            $table->integer('sorting_order')->default(9999);
            $table->boolean('animation_enabled')->default(false);
            $table->timestamps(0); // Automatically adds `created_at` and `updated_at` timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
}
