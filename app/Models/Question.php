<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded = [];
    public static function getExamTextQuestions($examId, $subjectId, $subjectType)
    {
        return Question::where([
            'exam_id' => $examId,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'question_type' => 'TEXT',
            'record_status' => 1
        ])->get();
    }

    public static function getExamMcqQuestions($examId, $subjectId, $subjectType)
    {
        return Question::where([
            'exam_id' => $examId,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'question_type' => 'MCQ',
            'record_status' => 1
        ])->get();
    }

    public static function getExamAllQuestions($examId, $subjectId, $subjectType)
    {
        return Question::where([
            'exam_id' => $examId,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'record_status' => 1
        ])->get();
    }
}
