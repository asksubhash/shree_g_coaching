<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'assignments';
    protected $guarded = [];

    public static function getStudentAssignments($courseId)
    {
        $query = Assignment::select(
            'assignments.*',
            'ins.name as institute_name',
            'c.course_name',
            'c.course_code',
            'ay.academic_year as academic_year',
            'ads.session_name as admission_session',
            's.name as language_subject',
            'nls.name as non_language_subject'
        );

        $query->leftJoin('institutes as ins', 'ins.id', '=', 'assignments.institute_id');
        $query->leftJoin('courses as c', 'c.id', '=', 'assignments.course_id');
        $query->leftJoin('academic_years as ay', 'assignments.academic_year_id', '=', 'ay.id');
        $query->leftJoin('admission_sessions as ads', 'assignments.admission_session_id', '=', 'ads.id');
        $query->leftJoin('subjects as s', function ($join) {
            $join->on('s.id', '=', 'assignments.subject_id');
            $join->where('assignments.subject_type', '=', 'LANGUAGE'); // Add your condition here
        });
        $query->leftJoin('non_language_subjects as nls', function ($join) {
            $join->on('nls.id', '=', 'assignments.subject_id');
            $join->where('assignments.subject_type', '=', 'NON_LANGUAGE'); // Add your condition here
        });
        $query->where('assignments.course_id', $courseId);
        $query->where('assignments.record_status', 1);
        return $query->get();
    }
}
