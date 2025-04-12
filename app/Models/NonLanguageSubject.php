<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NonLanguageSubject extends Model
{
    use HasFactory;

    public static function getNLSubjectWithExamTimings($examId)
    {
        return NonLanguageSubject::select(
            'non_language_subjects.*',
            'est.exam_date',
            'est.exam_time',
            'est.exam_duration',
            DB::raw("COUNT(qt.id) as questions_count")
        )
            ->rightJoin('exam_subject_timings as est', function ($join) use ($examId) {
                $join->on('est.subject_id', '=', 'non_language_subjects.id')
                    ->where('est.exam_id', '=', $examId)
                    ->where('est.subject_type', '=', 'NON_LANGUAGE');
            })
            ->leftJoin('questions as qt', function ($join) use ($examId) {
                $join->on('qt.subject_id', '=', 'non_language_subjects.id')
                    ->where('qt.exam_id', '=', $examId)
                    ->where('qt.subject_type', '=', 'NON_LANGUAGE');
            })
            ->where('non_language_subjects.record_status', 1)
            ->groupBy(
                'non_language_subjects.id',  // Ensure grouping by subject ID
                'non_language_subjects.name', // Add any other subject columns if needed
                'est.exam_date',
                'est.exam_time',
                'est.exam_duration'
            )
            ->get();
    }

    public static function getNLStudentSubjectWithExamTimings($subjectIds, $examId)
    {
        return NonLanguageSubject::select('non_language_subjects.*', 'est.exam_date', 'est.exam_time', 'est.exam_duration')
            ->leftJoin('exam_subject_timings as est', function ($join) use ($examId) {
                $join->on('est.subject_id', '=', 'non_language_subjects.id')
                    ->where('est.exam_id', '=', $examId)   // First additional condition
                    ->where('est.subject_type', '=', 'NON_LANGUAGE');  // Second additional condition
            })
            ->whereIn('non_language_subjects.id', $subjectIds)
            ->where('non_language_subjects.record_status', 1)
            ->get();
    }
}
