
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IsVisibleInAdmissionSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admission_sessions', function (Blueprint $table) {
            $table->renameColumn('is_verifyable', 'is_visible');
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
            $table->renameColumn('is_visible', 'is_verifyable');
        });
    }
}
