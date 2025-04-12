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
        Schema::create('role_menu_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('code', 200);
            $table->string('role_code', 200);
            $table->string('menu_code', 200);
            $table->integer('record_status')->default(1);
            $table->string('created_by', 20);
            $table->dateTime('created_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('updated_by', 20);
            $table->dateTime('updated_on')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_menu_mappings');
    }
};