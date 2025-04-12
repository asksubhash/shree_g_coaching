<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('study_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email_id', 191)->unique();
            $table->string('contact_no', 10)->unique();
            $table->string('address1', 50)->nullable();
            $table->string('address2', 255)->nullable();
            $table->integer('state')->nullable();
            $table->integer('district')->nullable();
            $table->string('city_name', 50)->nullable();
            $table->integer('pin_code')->nullable();
            $table->string('education_qualification', 100)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('nature_of_work', 100)->nullable();
            $table->integer('institute_name')->nullable();
            $table->enum('property', ['HIRE', 'LEASE', 'OWN'])->nullable();
            $table->string('passport_photo', 100)->nullable();
            $table->string('aadhar_card', 100)->nullable();
            $table->string('education_document', 100)->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->tinyInteger('is_blocked')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_centers');
    }
};
