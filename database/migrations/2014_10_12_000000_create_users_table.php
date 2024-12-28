<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('active');
            $table->boolean('banned');
            $table->string('register_ip');
            $table->string('country_code');
            $table->string('locale');
            $table->string('activation_key');
            $table->boolean('su');
            $table->rememberToken();
            $table->timestamps();
        });

        $user = \Laralum::newUser();
        $user->name = env('USER_NAME', 'admin');
        $user->email = env('USER_EMAIL', 'admin@admin.com');
        $user->password = bcrypt(env('USER_PASSWORD', 'admin123'));
        $user->active = true;
        $user->banned = false;
        $user->register_ip = "";
        $user->country_code = env('USER_COUNTRY_CODE', 'ES');
        $user->locale = env('USER_LOCALE', 'en');
        $user->activation_key = Str::random(25);  // Generates a random 25-character string
        $user->su = true;
        $user->save();

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
}
