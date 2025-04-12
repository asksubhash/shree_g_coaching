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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('role_code', 30);
            $table->string('menu_name', 100);
            $table->bigInteger('resource_id', 100);
            $table->float('sl_no');
            $table->string('icon_class', 50)->nullable();
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
        Schema::dropIfExists('menus');
    }
};
