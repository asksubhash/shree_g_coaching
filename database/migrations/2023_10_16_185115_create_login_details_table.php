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
        Schema::create('login_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('ip_address', 150);
            $table->dateTime('login_datetime');
            $table->string('status', 150);
            $table->string('current_status', 150);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_details');
    }
};
