<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Institute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarksEntry extends Model
{
    use HasFactory;

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }
}
