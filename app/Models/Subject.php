<?php

namespace App\Models;

use App\Models\CourseSubjectMapping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Subject extends Model
{
    use HasFactory;

    public function courseSubjectMapping()
    {
        return $this->hasOne(CourseSubjectMapping::class, 'subject_id');
    }

    public static function getSubjectWithExamTimings($examId)
    {
        return Subject::select(
            'subjects.*',
            'est.exam_date',
            'est.exam_time',
            'est.exam_duration',
            DB::raw("COUNT(qt.id) as questions_count")
        )
            ->rightJoin('exam_subject_timings as est', function ($join) use ($examId) {
                $join->on('est.subject_id', '=', 'subjects.id')
                    ->where('est.exam_id', '=', $examId)
                    ->where('est.subject_type', '=', 'LANGUAGE');
            })
            ->leftJoin('questions as qt', function ($join) use ($examId) {
                $join->on('qt.subject_id', '=', 'subjects.id')
                    ->where('qt.exam_id', '=', $examId)
                    ->where('qt.subject_type', '=', 'LANGUAGE');
            })
            ->where('subjects.record_status', 1)
            ->groupBy(
                'subjects.id',
                'subjects.name',
                'est.exam_date',
                'est.exam_time',
                'est.exam_duration'
            )
            ->get();
    }

    public static function getStudentSubjectWithExamTimings($subjectIds, $examId)
    {
        return Subject::select('subjects.*', 'est.exam_date', 'est.exam_time', 'est.exam_duration')
            ->leftJoin('exam_subject_timings as est', function ($join) use ($examId) {
                $join->on('est.subject_id', '=', 'subjects.id')
                    ->where('est.exam_id', '=', $examId)   // First additional condition
                    ->where('est.subject_type', '=', 'LANGUAGE');  // Second additional condition
            })
            ->whereIn('subjects.id', $subjectIds)
            ->where('subjects.record_status', 1)
            ->get();
    }
}
