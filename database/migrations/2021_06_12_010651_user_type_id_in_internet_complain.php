<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTypeIdInInternetComplain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internet_complains', function (Blueprint $table) {
            $table->foreignId('user_type_id')->nullable()->after('team_id');
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
            $table->dropForeign(['user_type_id']);
        });
    }
}
