<?php

namespace App\Models;

use App\Models\Institute;
use App\Models\CourseSubjectMapping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PDO;

class Course extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function courseSubjectMapping()
    {
        return $this->hasMany(CourseSubjectMapping::class, 'course_id', 'id');
    }

    public function courseNLSubjectMapping()
    {
        return $this->hasMany(CourseNLSubjectMapping::class, 'course_id', 'id');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id', 'id');
    }

    public static function getStudentCourseDetailsUsingId($id)
    {
        $query = Course::select('courses.*');
        $query->with('institute');
        $query->with('courseSubjectMapping.subject');
        $query->with('courseNLSubjectMapping.nl_subject');
        $query->where('id', $id);
        return $query->first();
    }

    public static function getDefaultInstCourses($courseFor = '')
    {
        $query = Course::where([
            'institute_id' => env('CENTER_INSTITUTE_ID'),
            'record_status' => 1
        ]);

        if ($courseFor) {
            $query->where([
                'course_for' => $courseFor
            ]);
        }

        $data = $query->get(['id', 'course_code', 'course_name']);
        return $data;
    }

    public static function getInstitutesCoursesUsingInsIds($instituteIdsArray, $courseFor = '')
    {
        $query = Course::where([
            'record_status' => 1
        ]);
        $query->whereIn('institute_id', $instituteIdsArray);
        if ($courseFor) {
            $query->where([
                'course_for' => $courseFor
            ]);
        }
        $data = $query->get(['id', 'course_code', 'course_name']);
        return $data;
    }

    public static function getAllCourses($courseFor)
    {
        $query = Course::where('record_status', 1);
        if ($courseFor) {
            $query->where([
                'course_for' => $courseFor
            ]);
        }
        $data = $query->get(['id', 'course_code', 'course_name']);
        return $data;
    }
}
