<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_department_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('department_id', 80);
            $table->bigInteger('user_id');
            $table->integer('record_status')->default(1);
            $table->string('created_by', 20);
            $table->string('updated_by', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_department_mappings');
    }
};
