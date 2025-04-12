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
        Schema::create('marks_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id');
            $table->integer('institute_id');
            $table->integer('course_id');
            $table->string('student_roll_no', 100);
            $table->integer('subject_id');
            $table->integer('marks_obtained');
            $table->string('marks_uploaded_file');
            $table->tinyInteger('record_status')->default(1);
            $table->string('created_by', 100);
            $table->string('updated_by', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks_entries');
    }
};
