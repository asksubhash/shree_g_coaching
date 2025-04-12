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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code', 50);
            $table->string('name', 100);
            $table->string('duration', 50)->nullable();
            $table->string('description', 255)->nullable();
            $table->integer('amount')->nullable();
            $table->string('created_by', 50);
            $table->dateTime('created_on');
            $table->string('updated_by', 50);
            $table->dateTime('updated_on');
            $table->tinyInteger('record_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
