<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssignment extends Model
{
    use HasFactory;

    protected $table = 'student_assignments';
    protected $guarded = [];

    public static function getStudentUploadedAssignment($assignmentId, $studentUserId)
    {
        $query = StudentAssignment::select('student_assignments.*');
        $query->where('student_assignments.assignment_id', $assignmentId);
        $query->where('student_assignments.user_id', $studentUserId);
        $query->where('student_assignments.record_status', 1);

        return $query->first();
    }
}
