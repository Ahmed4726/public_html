<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentFieldInStudentEmailApply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_email_applies', function (Blueprint $table) {
            $table->string('comment')->nullable()->after('global_status_id');
            $table->string('updated_by')->nullable()->constrained('users')->after('global_status_id');
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
            $table->dropColumn('comment');
            $table->dropForeign(['updated_by']);
        });
    }
}
