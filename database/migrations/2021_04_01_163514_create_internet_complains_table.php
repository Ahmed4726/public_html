<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternetComplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internet_complains', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('department_id')->nullable();
            $table->string('other')->nullable();
            $table->foreignId('internet_complain_category_id');
            $table->text('details');
            $table->string('email');
            $table->string('phone_no');
            $table->foreignId('global_status_id');
            $table->string('solved_by')->constrained('users')->nullable();
            $table->timestamp('solved_at')->nullable();
            $table->string('rejected_by')->constrained('users')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('rejected_comment')->nullable();
            $table->string('updated_by')->constrained('users')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internet_complains');
    }
}
