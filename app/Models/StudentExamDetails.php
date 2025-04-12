<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExamDetails extends Model
{
    use HasFactory;

    protected $table = 'student_exams_details';
    protected $guarded = [];
}
