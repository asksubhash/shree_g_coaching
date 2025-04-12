<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubjectTiming extends Model
{
    use HasFactory;
    protected $table = 'exam_subject_timings';
    protected $guarded = [];
}
