<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConfigFieldInAdministrativeMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('administrative_members', function (Blueprint $table) {
            $table->string('config')->after('name')->default('{"message":"Message","address":"Address"}');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('administrative_members', function (Blueprint $table) {
            $table->dropColumn('config');
        });
    }
}
