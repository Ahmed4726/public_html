<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CheckedByCheckedAtColumnInStudentEmailAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_email_applies', function (Blueprint $table) {
            $table->timestamp('checked_at')->nullable()->after('updated_at');
            $table->string('checked_by')->nullable()->constrained('users')->after('updated_by');
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
            $table->dropColumn('checked_at');
            $table->dropForeign(['checked_by']);
        });
    }
}
