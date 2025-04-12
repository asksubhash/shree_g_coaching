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
        Schema::create('student_academic_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id')->nullable();
            $table->integer('class_id')->nullable();
            $table->integer('academic_year')->nullable();
            $table->integer('admission_session_id')->nullable();
            $table->string('medium_off_inst',50)->default("HINDI");
            $table->string('created_by', 50);
            $table->string('updated_by', 50);
            $table->tinyInteger('record_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_academic_details');
    }
};
