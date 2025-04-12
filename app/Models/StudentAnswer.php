<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $table = 'student_answers';
    protected $guarded = [];

    public static function getStudentMCQAnswerUsingSEDID($studentExamId)
    {
        return StudentAnswer::select('student_answers.*', 'q.question_type')
            ->leftJoin('questions as q', 'q.id', '=', 'student_answers.question_id')
            ->where([
                'student_answers.student_exam_details_id' => $studentExamId,
                'student_answers.record_status' => 1,
                'q.question_type' => 'MCQ'
            ])->get();
    }

    public static function getStudentTextAnswerUsingSEDID($studentExamId)
    {
        return StudentAnswer::select('student_answers.*', 'q.question_type')
            ->leftJoin('questions as q', 'q.id', '=', 'student_answers.question_id')
            ->where([
                'student_answers.student_exam_details_id' => $studentExamId,
                'student_answers.record_status' => 1,
                'q.question_type' => 'TEXT'
            ])->get();
    }
}
