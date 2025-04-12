<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubjectMapping extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "student_subject_mappings";

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public static function getStudentSubjectsUsingStudentId($id)
    {
        return StudentSubjectMapping::select('student_subject_mappings.*', 'sub.name as subject_name')
            ->leftJoin('subjects as sub', 'sub.id', '=', 'student_subject_mappings.subject_id')
            ->where('student_subject_mappings.student_id', $id)
            ->where('student_subject_mappings.record_status', 1)
            ->get();
    }
}
