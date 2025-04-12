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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 100);
            $table->string('f_name', 100);
            $table->string('l_name', 100);
            $table->string('mobile_no', 10)->unique();
            $table->string('email_id', 191)->unique();
            $table->string('district_code', 20)->nullable();
            $table->date('dob')->nullable();
            $table->string('designation', 20)->nullable();
            $table->string('profile_photo', 80)->default('avatar.png');
            $table->tinyInteger('is_verified')->default(1);
            $table->tinyInteger('is_blocked')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
