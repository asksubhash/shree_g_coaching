<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseSubjectMapping extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'subject_id', 'updated_by', 'updated_at', 'record_status', 'created_at', 'created_by'];
    public $timestamps = true;

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
