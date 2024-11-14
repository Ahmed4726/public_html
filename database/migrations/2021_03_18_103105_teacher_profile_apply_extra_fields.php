<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TeacherProfileApplyExtraFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->boolean('is_applied')->default(false);
            $table->string('completed_by')->constrained('users')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('rejected_by')->constrained('users')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['is_applied', 'completed_by', 'completed_at', 'rejected_by', 'rejected_at', 'comment']);
        });
    }
}
