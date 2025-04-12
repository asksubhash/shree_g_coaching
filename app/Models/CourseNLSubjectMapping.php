<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseNLSubjectMapping extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'subject_id', 'updated_by', 'updated_at', 'record_status', 'created_at', 'created_by'];
    public $timestamps = true;
    protected $table = 'course_nl_subject_mappings';

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function nl_subject()
    {
        return $this->belongsTo(NonLanguageSubject::class, 'subject_id', 'id');
    }
}
