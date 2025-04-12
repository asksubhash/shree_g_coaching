<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentNLSubjectMapping extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "student_nl_subject_mappings";

    public function nl_subject()
    {
        return $this->belongsTo(NonLanguageSubject::class, 'subject_id', 'id');
    }

    public static function getStudentNLSubjectsUsingStudentId($id)
    {
        return StudentNLSubjectMapping::select('student_nl_subject_mappings.*', 'sub.name as subject_name')
            ->leftJoin('non_language_subjects as sub', 'sub.id', '=', 'student_nl_subject_mappings.subject_id')
            ->where('student_nl_subject_mappings.student_id', $id)
            ->where('student_nl_subject_mappings.record_status', 1)
            ->get();
    }
}
