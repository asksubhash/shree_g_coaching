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
        Schema::create('state_masters', function (Blueprint $table) {
            $table->id();
            $table->string('state_name', 50);
            $table->string('country_code', 3)->default('IND');
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
        Schema::dropIfExists('state_masters');
    }
};
