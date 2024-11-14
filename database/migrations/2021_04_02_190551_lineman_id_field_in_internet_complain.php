<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LinemanIdFieldInInternetComplain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internet_complains', function (Blueprint $table) {
            $table->foreignId('lineman_id')->after('department_id')->nullable();
            $table->foreignId('assign_by')->after('global_status_id')->nullable();
            $table->timestamp('assign_at')->after('assign_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internet_complains', function (Blueprint $table) {
            $table->dropColumn(['lineman_id', 'assign_by', 'assign_at']);
        });
    }
}
