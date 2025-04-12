<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamStudentsEnrollment extends Model
{
    use HasFactory;

    protected $table = 'exam_students_enrollment';
    protected $guarded = [];

    public static function getStudentExamEnrollmentUsingStudentId($studentId)
    {
        return ExamStudentsEnrollment::select('*')
            ->where([
                'student_id' => $studentId,
                'is_enrolled' => 1,
                'admit_card' => 1,
            ])
            ->first();
    }
}
