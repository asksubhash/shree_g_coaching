<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';
    protected $guarded = [];

    public static function getExamUsingExamId($id)
    {
        return Exam::select('exams.*', 'ay.academic_year', 'as.session_name')
            ->leftJoin('academic_years as ay', 'ay.id', '=', 'exams.academic_year_id')
            ->leftJoin('admission_sessions as as', 'as.id', '=', 'exams.admission_session_id')
            ->where([
                'exams.id' => $id,
                'exams.is_published' => 1
            ])->first();
    }
}
