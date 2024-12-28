<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdministrativeMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrative_members', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with current timestamp
            $table->bigInteger('created_by')->default(-1); // created_by field
            $table->bigInteger('updated_by')->default(-1); // updated_by field
            $table->string('name', 255); // name field (not nullable)
            $table->string('designation', 255)->nullable(); // designation field (nullable)
            $table->string('department', 255)->nullable(); // department field (nullable)
            $table->text('image_url')->nullable(); // image_url field (nullable)
            $table->text('address')->nullable(); // address field (nullable)
            $table->bigInteger('teacher_id')->nullable(); // teacher_id field (nullable)
            $table->string('primary_email', 64)->nullable(); // primary_email field (nullable)
            $table->string('other_emails', 255)->nullable(); // other_emails field (nullable)
            $table->text('website_link')->nullable(); // website_link field (nullable)
            $table->string('fax', 64)->nullable(); // fax field (nullable)
            $table->string('fabx', 64)->nullable(); // fabx field (nullable)
            $table->text('message')->nullable(); // message field (nullable)
            $table->text('biography')->nullable(); // biography field (nullable)
            $table->integer('sorting_order')->default(99); // sorting_order field with a default value of 99
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administrative_members');
    }
}
