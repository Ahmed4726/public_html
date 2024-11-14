<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternetConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internet_connections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('employee_type_id');
            $table->foreignId('department_id')->nullable();
            $table->string('other')->nullable();
            $table->text('address');
            $table->string('email');
            $table->string('phone_no');
            $table->text('comment')->nullable();
            $table->foreignId('global_status_id');
            $table->string('completed_by')->constrained('users')->nullable();
            $table->timestamp('completed_at')->nullable();
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
        Schema::dropIfExists('internet_connections');
    }
}
