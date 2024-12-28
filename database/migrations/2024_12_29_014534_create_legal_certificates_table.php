<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalCertificatesTable extends Migration
{
    public function up()
    {
        Schema::create('legal_certificates', function (Blueprint $table) {
            $table->id();
            $table->timestamps(0);
            $table->bigInteger('created_by')->default(-1);
            $table->bigInteger('updated_by')->default(-1);
            $table->text('name');
            $table->string('designation', 64)->nullable();
            $table->text('address')->nullable();
            $table->text('path')->nullable();
            $table->string('date_prev')->nullable();
            $table->integer('status')->default(0);
            $table->string('type')->default('NOC');
            $table->integer('type_id')->nullable();
            $table->bigInteger('serial')->default(0);
            $table->text('external_link')->nullable();
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('legal_certificates');
    }
}
