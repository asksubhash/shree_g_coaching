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
        Schema::create('student_details', function (Blueprint $table) {
            $table->id();
            $table->enum('edu_type', [10, 12]);
            $table->string('adm_sesh', 50);
            $table->string('adm_type', 50);
            $table->integer('course');
            $table->string('lang_subj', 30);
            $table->string('non_lang_subj', 30);
            $table->string('voc_subj', 50);
            $table->string('name', 40);
            $table->string('father_name', 30);
            $table->string('mother_name', 30);
            $table->string('gender', 50);
            $table->date('dob');
            $table->string('religion', 50);
            $table->longText('address');
            $table->string('pincode', 6);
            $table->string('post_office', 50);
            $table->integer('district');
            $table->integer('state');
            $table->string('email', 120);
            $table->string('contact_number', 10);
            $table->string('marital_status', 50);
            $table->string('nationality', 50);
            $table->string('category', 50);
            $table->string('aadhar_number', 12);
            $table->enum('medium_off_inst', ['HINDI', 'ENGLISH', 'NEPALI'])->default('HINDI');

            // guardian parent  detail 
            $table->string('guardian_name', 30)->nullable();
            $table->string('guardian_occ', 100)->nullable();
            $table->string('guardian_con', 10)->nullable();
            $table->string('guardian_email', 120)->nullable();

            // metric  detail 
            $table->string('met_subj', 30)->nullable();
            $table->string('met_year', 50)->nullable();
            $table->string('met_board', 100)->nullable();
            $table->string('met_ob_mark', 10)->nullable();
            $table->string('met_max_mark', 10)->nullable();
            $table->string('met_max_per', 10)->nullable();
            $table->string('met_mrk_sheet', 120)->nullable();

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
        Schema::dropIfExists('student_details');
    }
};
