<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IsVerifyAbleFieldInAdmissionSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admission_sessions', function (Blueprint $table) {
            $table->boolean('is_verifyable')->default(0)->after('sorting_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admission_sessions', function (Blueprint $table) {
            $table->dropColumn('is_verifyable');
        });
    }
}
