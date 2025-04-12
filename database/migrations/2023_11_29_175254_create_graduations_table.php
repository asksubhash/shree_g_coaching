<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Node\Block\Document;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('graduations', function (Blueprint $table) {
            $table->id();
            $table->string('adm_sesh', 50);
            $table->string('course', 50);
            $table->string('name', 40);
            $table->string('father_name', 30);
            $table->string('mother_name', 30);
            $table->string('gender', 50);
            $table->date('dob');
            $table->longText('address');
            $table->string('pincode', 6);
            $table->integer('state_id');
            $table->string('email', 120);
            $table->string('contact_number', 10);
            $table->string('category', 50);
            $table->string('aadhar_number', 12);


            // Academic  detail 
            // Academic 10th detail 
            $table->string('ac_ten_year', 50)->nullable();
            $table->string('ac_ten_subj', 50)->nullable();
            $table->string('ac_ten_board', 100)->nullable();
            $table->string('ac_ten_board_name', 50)->nullable();
            $table->string('ac_ten_sheet', 120)->nullable();

            // Academic 12th detail 
            $table->string('ac_twelve_year', 50)->nullable();
            $table->string('ac_twelve_subj', 50)->nullable();
            $table->string('ac_twelve_board', 100)->nullable();
            $table->string('ac_twelve_board_name', 50)->nullable();
            $table->string('ac_twelve_sheet', 120)->nullable();

            // Academic other detail 
            $table->string('ac_other_year', 50)->nullable();
            $table->string('ac_other_subj', 50)->nullable();
            $table->string('ac_other_board', 100)->nullable();
            $table->string('ac_other_board_name', 50)->nullable();
            $table->string('ac_other_sheet', 120)->nullable();

            // Document details 
            $table->string('photo', 120)->nullable();
            $table->string('aadhar', 120)->nullable();
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
        Schema::dropIfExists('graduations');
    }
};
