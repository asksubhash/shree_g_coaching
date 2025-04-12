<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('resource_name', 100);
            $table->string('resource_link', 100);
            $table->integer('is_maintenance')->default(1);
            $table->string('created_by', 50);
            $table->dateTime('created_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('updated_by', 50);
            $table->dateTime('updated_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('record_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
