<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionSession extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getAdmissionSessUsingAyInsCourse($academicYearId, $instituteId, $courseId)
    {
        return AdmissionSession::where([
            'academic_year_id' => $academicYearId,
            'institute_id' => $instituteId,
            'course_id' => $courseId,
        ])->get();
    }
}
