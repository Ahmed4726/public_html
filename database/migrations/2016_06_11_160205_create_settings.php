<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettings extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_on')->useCurrent();
            $table->timestamp('updated_on')->useCurrent()->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->bigInteger('created_by')->default(-1);
            $table->bigInteger('updated_by')->default(-1);
            $table->string('type', 64)->default('UNIVERSITY');
            $table->string('email', 64)->nullable();
            $table->string('phone', 16)->nullable();
            $table->string('fax', 64)->nullable();
            $table->text('address')->nullable();
            $table->text('logo_url')->nullable();
            $table->text('header_text')->nullable();
            $table->text('footer_text')->nullable();
            $table->text('copyright_text')->nullable();
            $table->text('owner_image_url')->nullable();
            $table->text('owner_msg')->nullable();
            $table->string('contact_us_link', 255)->nullable();
            $table->string('jobs_link', 255)->nullable();
            $table->integer('max_profile_image_size_in_kb')->default(0);
            $table->string('webmail_link', 255)->nullable();
            $table->boolean('hall_enabled')->default(0);
            $table->boolean('featured_news_enabled')->default(0);
            $table->integer('max_discussion_image_size_in_kb')->default(0);
            $table->string('about_us_link', 255)->nullable();
            $table->string('mission_and_vission_link', 255)->nullable();
            $table->boolean('animate_header_admission_link')->default(0);
            $table->integer('banner_image_limit')->default(3);
            $table->string('facebook_link', 255)->nullable();
            $table->string('twitter_link', 255)->nullable();
            $table->string('linkedin_link', 255)->nullable();
            $table->text('top_contact_menu')->nullable();
            $table->text('welcome_message')->nullable();
            $table->longText('custom_css')->nullable();
            $table->longText('custom_js')->nullable();
            $table->string('home_first_section_event', 64)->nullable();
            $table->string('home_second_section_event', 64)->nullable();
            $table->string('home_third_section_event', 64)->nullable();
            $table->string('department_event', 64)->nullable();
            $table->string('default_password_new_user', 64)->nullable();
            $table->integer('frontend_pagination_number')->default(20);
            $table->integer('backend_pagination_number')->default(30);
            $table->integer('spotlight_number')->default(5);
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
