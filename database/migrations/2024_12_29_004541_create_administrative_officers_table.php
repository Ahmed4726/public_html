<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdministrativeOfficersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrative_officers', function (Blueprint $table) {
            $table->id(); // Bigint auto-incrementing ID
            $table->timestamps(0); // created_on and updated_on with default CURRENT_TIMESTAMP
            $table->bigInteger('created_by')->default(-1); // created_by field with default value -1
            $table->bigInteger('updated_by')->default(-1); // updated_by field with default value -1
            $table->string('type', 64)->default('SENATE'); // type field with default value 'SENATE'
            $table->string('name', 255)->nullable(); // name field (nullable)
            $table->string('rank', 64)->default('MEMBER'); // rank field with default value 'MEMBER'
            $table->text('image_url')->nullable(); // image_url field (nullable)
            $table->string('department', 255)->nullable(); // department field (nullable)
            $table->text('address')->nullable(); // address field (nullable)
            $table->bigInteger('teacher_id')->nullable(); // teacher_id field (nullable)
            $table->string('primary_email', 64)->nullable(); // primary_email field (nullable)
            $table->string('other_emails', 255)->nullable(); // other_emails field (nullable)
            $table->text('website_link')->nullable(); // website_link field (nullable)
            $table->string('fax', 64)->nullable(); // fax field (nullable)
            $table->string('fabx', 64)->nullable(); // fabx field (nullable)
            $table->text('message')->nullable(); // message field (nullable)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administrative_officers');
    }
}
