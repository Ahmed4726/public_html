<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompletedByRejectedByWithAtInStudentEmailApplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_email_applies', function (Blueprint $table) {
            $table->timestamp('rejected_at')->nullable()->after('checked_at');
            $table->timestamp('completed_at')->nullable()->after('checked_at');
            $table->string('rejected_by')->nullable()->constrained('users')->after('updated_by');
            $table->string('completed_by')->nullable()->constrained('users')->after('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_email_applies', function (Blueprint $table) {
            $table->dropColumn(['rejected_at', 'completed_at', 'rejected_by', 'completed_by']);
        });
    }
}
