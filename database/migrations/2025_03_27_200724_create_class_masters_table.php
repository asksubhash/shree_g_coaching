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
        Schema::create('class_masters', function (Blueprint $table) {
            $table->id();
            $table->integer('institute_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('difficulty_level', ["BEGINNER", "INTERMEDIATE", "ADVANCED"])->nullable();
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
        Schema::dropIfExists('class_masters');
    }
};
