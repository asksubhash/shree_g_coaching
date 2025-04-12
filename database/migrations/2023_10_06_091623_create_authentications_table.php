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
        Schema::create('authentications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('username', 191);
            $table->string('password', 191);
            $table->varchar('role_code', 50);
            $table->integer('record_status')->default(1);
            $table->string('created_by', 50);
            $table->dateTime('created_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('updated_by', 50);
            $table->dateTime('updated_on')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authentications');
    }
};
