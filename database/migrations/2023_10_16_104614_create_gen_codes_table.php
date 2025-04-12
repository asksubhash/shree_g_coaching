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
        Schema::create('gen_codes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gen_code_group_id');
            $table->bigInteger('gen_code');
            $table->string('description');
            $table->bigInteger('serial_no');
            $table->integer('status')->default(1);
            $table->string('created_by', 50);
            $table->string('updated_by', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gen_codes');
    }
};
